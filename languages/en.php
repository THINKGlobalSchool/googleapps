<?php
/**
 * Googleapps english translation
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$english = array(
	
		// Entity/Object labels
		'item:object:site_activity' => 'Wiki activity',
    	'item:object:shared_doc' => 'Google Shared Document',
		'item:user:googleapps' => 'googleapps users',
		
		// Login related
		'googleapps:title' => 'Sign in with Google Apps - settings',
		'googleapps:details' => 'To use Sign in with Google Apps, you have to enter your Google Apps (hosted) domain in the field below. Also you can leave it blank to use with username@gmail.com accounts.\nIf you want to provide futher integration with Google you need to register your site with Google Apps and obtain consumer key and secret. Your site should be registered with a high level of security (with a X.509 certificate). You also should enter your Secret in the text field below.',
		
		// Admin Settings
		'googleapps:domain' => 'Google Apps (hosted) domain name without www, http://, etc (example: thinkglobalschool.com)',
		'googleapps:secret' => 'Secret (to use with OAuth)',
		'googleapps:privatekey' => 'Private RSA key (to use with OAuth)',
		'googleapps:settings:yes' => 'yes',
		'googleapps:settings:no' => 'no',

		// User settings
		'googleapps:googleapps_user_settings_title' => 'googleapps profile',
		'googleapps:googleapps_user_settings_sync_email' => 'Synchronize with google mail.',
		'googleapps:googleapps_user_settings_sync_sites' => 'Synchronize with google sites.',
    	'googleapps:googleapps_user_settings_sync_docs' => 'Synchronize with google docs.',
		'googleapps:googleapps_user_settings:save:ok' => 'Your googleapps profile preference has been saved.',
		'googleapps:googleapps_login_description' => 'Connect your Spot user account with your Google Apps account.',
		'googleapps:googleapps_login_settings:save:ok' => 'Your googleapps screen name has been saved.',
		'googleapps:googleapps_login_title' => 'Googleapps login',
		'googleapps:google_sites_settings' => 'Wiki Activity Settings',
		'googleapps:google_sites_settings_description' => 'Set who gets to see wiki activity for the wikis you are in.  NOTE: These settings control the publish activity for everyone. Take care when making changes here.',
		'googleapps:google_sync_settings' => 'Google Apps Settings',
		'googleapps:google_sync_settings_description' => 'Manage your connection to Google Apps',

		// Error messages
		'googleapps:account_create' => 'Error: Unable to create your account. Please contact the site administrator or try again later.',
		'googleapps:inactive' => 'Error: cannot activate your Elgg account.',
		'googleapps:banned' => 'Error: cannot log you in. Please contact the site administrator or try again later.',
		'googleapps:googleappserror' => 'Error: googleapps returned the following error message: %s',
		'googleapps:account_duplicate' => 'Error: a non-googleapps account with the same username (%s) already exists on this site.',
		'googleapps:wrongdomain' => 'Error: cannot resolve OpenID entrypoint for your GoogleApps (hosted) domain or domain is not google-hosted.',
		'googleapps:passwordrequired' => 'Please provide your password before you stop synchronizing with googleapps.',
		'googleapps:passwordrequired:disconnect' => 'Please provide your password before you disconnect profile from googleapps.',
		'googleapps:notauthorized' => 'Not authorized',
		'googleapps:nodata' => 'No data',
		'googleapss:usernotready' => 'This user is not ready for synchronization.',
		'googleapps:noemail' => 'No email', 
		'googleapps:emailexists' => 'Sorry, but email %s already exists and in use by other user.',
		'googleapps:saveshareddocerror' => 'There was an error sharing the document.',
		'googleapps:doc:share:no_doc_id' => '<h2>Uh oh..</h2><p class="googleappsdialog_message">You need to select a document first.</p>',
    	'googleapps:doc:share:wrong_permissions' => 'Document permissions need to be updated',
		'googleapps:doc:share:invalid_url' => 'Invalid Google Document URL',
		
		// Success messages
		'googleapps:success:disconnect' => 'Your profile has been successfully disconnected from googleapps.', 
		'googleapps:doc:share:ok' => '<h2>Success!</h2><p class="googleappsdialog_message">Document shared</p>',
		'googleapps:googlesitesreset' => 'Google Sites have been successfully reset',
		
		// General labels
		'googleapps:google_docs' => 'Google Docs',
		'googleapps:google_docs:description' => '',
		'googleapps:oauth_update_interval' => 'Time interval of unread email update (in minutes)',
		'googleapps:sites' => 'Wikis',
		'googleapps:site' => 'Wiki',
		'googleapps:sites:your' => 'Your Wikis',
		'googleapps:sites:everyone' => 'All Wikis',
		'googleapps:sites:all' => 'All Wikis',
		'googleapps:site:user' => "%s's wiki",
		'googleapps:site:add' => 'Create new Wiki',
		'googleapps:admindebugtitle' => 'Google Sites Debug',
		'googleapps:access_level' => 'Access Level',
		'googleapps:docsloading' => 'Loading...',
		'googleapps:tab:share_url' => 'Enter URL',
		'googleapps:tab:share_browse' => 'Browse Documents',
		'googleapps:doc:match_permissions' => 'Match permissions of Google Document?',
		'googleapps:label:action_required' => 'Action Required',
		
		// Permissions submit buttons
		'googleapps:submit:grant' => 'Grant view permissons',
		'googleapps:submit:ignore' => 'Ignore and continue',
		'googleapps:submit:cancel' => 'Cancel',
		
		// River
		'googleapps:river:shared_doc:create' => '%s shared a Google Document titled ',
  );

add_translation('en',$english);

?>
