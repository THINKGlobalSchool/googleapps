<?php
/**
 * Google disconnect action
 * - Handles disconnecting a google apps account from a spot account
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */


$user = elgg_get_logged_in_user_entity();

if (!$user) {
	forward();
}

if ($user->google || $user->google_connected) { // Note: $user->google is old metadata
	// Make sure there's a password
	if (empty($user->password)) {
		register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), elgg_echo('googleapps:error:passworddisconnect')));
		forward(REFERER);
	}

	// Get client to revoke user access token(s)
	$client = googleapps_get_client();
	$client->setAccessToken(json_encode(array('access_token' => $user->google_access_token)));
	
	// Try revoking
	if ($client->revokeToken($user->google_refresh_token)) {
		// Metadata options
		$options = array(
			'guid' => $user->guid,
			'limit' => 0,
			'metadata_names' => array(
				'sync', 'connect', 'googleapps_controlled_profile', 'google', 'google_access_token', 
				'google_refresh_token', 'access_token', 'access_token', 'google_connected'
			),
		);

		// Delete all (and old) google metadata
		elgg_delete_metadata($options);

		$user->save();

		// Clear session data
		_elgg_services()->session->remove('google_access_token');
		_elgg_services()->session->remove('google_login_state');


		system_message(elgg_echo('googleapps:success:disconnect'));
	} else {
		system_message(elgg_echo('googleapps:error:disconnect'));
	}
} else {
	register_error('googleapps:error:notconnected');
}

forward('googleapps/settings/account');
