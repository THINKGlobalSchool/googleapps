<?php
/**
 * Googleapps login callback
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

// Get allowed domain for validation
$google_api_domain = elgg_get_plugin_setting('google_api_domain', 'googleapps');

// Get state values
$state = get_input('state', FALSE);
$g_state = _elgg_services()->session->get('google_login_state');

// Validate anti-forgery state values
if (!$g_state || !$state || $g_state != $state) {
	register_error(elgg_echo('googleapps:error:invalidstate'));
	forward();
}

// Check for errors
if ($error = get_input('error', FALSE)) {
	register_error(elgg_echo('googleapps:error:loginerror', array($error)));
	forward();
}

// Set up client
$redirect_uri = elgg_get_site_url() . "googleapps/auth/callback";

// Create client
$client = googleapps_get_client();
$client->setState($state);

// First check for the authentication code
if ($code = get_input('code', FALSE)) {

	// Authenticate client
	$client->authenticate($code);

	// Get access token
	$access_token = $client->getAccessToken();

	// New state
	$state = md5(rand());

	// Set access token and new state in session
	_elgg_services()->session->set('google_access_token', $access_token);
	_elgg_services()->session->set('google_login_state', $state);

	// Redirect back to this callback with the access_token set to the session
	header('Location: ' . filter_var($redirect_uri . "?state={$state}", FILTER_SANITIZE_URL));

} else if (_elgg_services()->session->get('google_access_token')) {
	// Got an access token, set on the client
	$client->setAccessToken(_elgg_services()->session->get('google_access_token'));
} else {
	// Missing code/invalid access_token, etc
	register_error(elgg_echo('googleapps:error:loginerror', array(elgg_echo('googleapps:error:missinglogin'))));
	forward();
}

if ($client->getAccessToken()) {
	_elgg_services()->session->set('google_access_token', $client->getAccessToken());
	$token_data = $client->verifyIdToken()->getAttributes();
}

// Create plus service
$service = new Google_Service_Plus($client);

// Extract userid from token payload
$userid = $token_data['payload']['sub'];

// Get access tokens (access and refresh)
$access_tokens = json_decode($client->getAccessToken());

if (!$access_tokens instanceof stdClass) {
	register_error('googleapps:error:accesstokens');
	forward();
}

// Get user info
$user = $service->people->get($userid);

// Get user name
$user_given_name = $user->getName()->getGivenName();
$user_family_name = $user->getName()->getFamilyName();
$user_full_name = "{$user_given_name} {$user_family_name}";

// Get user email
foreach ($user->getEmails() as $email) {
	$user_email = $email->getValue();
}

// Check for valid email domain
$allowed = array($google_api_domain);

// Include subdomains from settings
foreach (googleapps_get_allowed_subdomains() as $subdomain) {
	$allowed[] = $subdomain . '.' . $google_api_domain;
}

$domain = array_pop(explode('@', $user_email));

if (!in_array($domain, $allowed)) {
	// Invalid domain, get outta here and revoke the token
	$client->revokeToken($access_tokens->refresh_token);
	register_error(elgg_echo('googleapps:error:notauthorized'));
	forward();
}

// User if valid and logged authenticated, lets see if we have an Elgg user
$entities = get_user_by_email($user_email);

// User does not exist
if (!$entities) {
	$username = preg_replace("/\@[a-zA-Z\.0-9\-]+$/", "", $user_email);

	// Check username against hidden user entities
	$access = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	// Check if there's a user with this username
	if (get_user_by_username($username)) {
		// Yep, bail
		register_error(elgg_echo('googleapps:error:account_duplicate', array($username)));
		forward();
	}

	access_show_hidden_entities($access);
	
	// Create a password for the user
	$password = generate_random_cleartext_password();
	
	// Try registering user	
	$guid = register_user($username, $password, $user_full_name, $user_email, FALSE);
	
	// Good to go, set up account info
	if ($guid) {
		$user = get_entity($guid);

		$user->google_connected = TRUE;

		elgg_set_user_validation_status($user->guid,TRUE);

		// Turn on email notifications by default
		set_user_notification_setting($user->getGUID(), 'email', TRUE);

		// Send user an email confirming that the account is created and supply them with a password, just in case
		notify_user($user->guid,
			elgg_get_site_entity()->guid,
			elgg_echo('googleapps:email:created:subject'),
			elgg_echo('googleapps:email:created:body', array(
				$user->username, 
				$user->username, 
				$password,
				elgg_get_site_url() . 'settings/user/' . $user->username)),
			NULL,
			'email'
		);
		$login_message = elgg_echo('googleapps:success:connect', array($user->name));
	} else {
		register_error(elgg_echo("googleapps:error:account_create"));
		forward();
	}
} else {
	// Got a user with this email
	$user = $entities[0];

	// Check if we're simply connecting/reconnecting an account
	if (!_elgg_services()->session->get('google_connect_account')) {
		// Make sure user is a the google connected user (may have disconnected)
		$ia = elgg_get_ignore_access();
		elgg_set_ignore_access(true);

		if (!$user->google_connected) {
			elgg_set_ignore_access($ia);

			// Revoke tokens
			$client->revokeToken($access_tokens->refresh_token);

			register_error(elgg_echo('googleapps:error:existing_account'));
			forward();
		}

		elgg_set_ignore_access($ia);
	} else {
		// Update google connection status
		_elgg_services()->session->set('last_forward_from', elgg_normalize_url('googleapps/settings/account'));
		$user->google_connected = TRUE;
	}

	// Check if user is banned
	if (isset($user->banned) && $user->banned == 'yes') {
		register_error(elgg_echo("googleapps:error:banned"));
		forward();
	}
	$login_message = elgg_echo('loginok');
}

// Start the login process
$persistant = TRUE;

// Check sync settings for additional tasks
$user_sync_settings = unserialize($user->sync_settings);

$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

// Create/set latest token metadata
create_metadata($user->guid, 'google_access_token', $access_tokens->access_token, '', $user->guid, ACCESS_PRIVATE);

// Will be available on first connection ONLY
if ($access_tokens->refresh_token) {
	create_metadata($user->guid, 'google_refresh_token', $access_tokens->refresh_token, '', $user->guid, ACCESS_PRIVATE);
}

// Update name and email if enabled
if (($user->google_connected) && $user_sync_settings['sync_name']!== 0) {
	// update from Google
	$user->email = $user_email;
	$user->name = !empty($user_full_name) ? $user_full_name : $user_email;
	$user->save();
}

elgg_set_ignore_access($ia);

// Login if applicable
if (!_elgg_services()->session->get('google_connect_account')) {
	login($user, $persistant);
	system_message($login_message);
} else {
	// Just a connection, display success message and unset session var
	_elgg_services()->session->set('google_connect_account', FALSE);
	system_message(elgg_echo('googleapps:success:manual_connect'));
}

// Forward on
if (_elgg_services()->session->get('google_connect_alt_forward')) {
	$forward_url = _elgg_services()->session->get('google_connect_alt_forward');
	_elgg_services()->session->set('google_connect_alt_forward', null);
	forward($forward_url);
} else if (_elgg_services()->session->get('last_forward_from')) {
	$forward_url = _elgg_services()->session->get('last_forward_from');
	_elgg_services()->session->set('last_forward_from', null);
	forward($forward_url);
} else {
	forward();
}
