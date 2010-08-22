<?php

$english = array(
		'item:user:googleapps' => 'googleapps users',
		'googleappslogin:title' => 'Sign in with Google Apps - settings',
		'googleappslogin:details' => 'To use Sign in with Google Apps, you have to enter your Google Apps (hosted) domain in the field below. Also you can leave it blank to use with username@gmail.com accounts.\nIf you want to provide futher integration with Google you need to register your site with Google Apps and obtain consumer key and secret. Your site should be registered with a high level of security (with a X.509 certificate). You also should enter your Secret in the text field below.',
		
		'googleappslogin:domain' => 'Google Apps (hosted) domain name without www, http://, etc (example: thinkglobalschool.com)',
		'googleappslogin:secret' => 'Secret (to use with OAuth)',
		'googleappslogin:privatekey' => 'Private RSA key (to use with OAuth)',

		'googleappslogin:account_create' => 'Error: Unable to create your account. '
				.'Please contact the site administrator or try again later.',
		'googleappslogin:inactive' => 'Error: cannot activate your Elgg account.',
		'googleappslogin:banned' => 'Error: cannot log you in. '
				.'Please contact the site administrator or try again later.',
		'googleappslogin:googleappserror' => 'Error: googleapps returned the following error message: %s',
		'googleappslogin:account_duplicate' => 'Error: a non-googleapps account with the same username (%s) already exists on this site.',

		'googleappslogin:wrongdomain' => 'Error: cannot resolve OpenID entrypoint for your GoogleApps (hosted) domain or domain is not google-hosted.',
		'googleappslogin:settings:yes' => 'yes',
		'googleappslogin:settings:no' => 'no',

		'googleappslogin:googleapps_user_settings_title' => 'googleapps profile',
		'googleappslogin:googleapps_user_settings_sync_email' => 'Synchronize with google mail.',
		'googleappslogin:googleapps_user_settings_sync_sites' => 'Synchronize with google sites.',
    'googleappslogin:googleapps_user_settings_sync_docs' => 'Synchronize with google docs.',

		'googleappslogin:googleapps_user_settings:save:ok' => 'Your googleapps profile preference has been saved.',
		'googleappslogin:googleapps_login_settings:save:ok' => 'Your googleapps screen name has been saved.',
		'googleappslogin:googleapps_login_title' => 'Googleapps login',
		'googleappslogin:googleapps_login_description' => 'Connect your Spot user account with your Google Apps account.',

		'googleappslogin:google_docs' => 'Share Google docs',
		'googleappslogin:google_docs:description' => '',

		'googleappslogin:google_sites_settings' => 'Wiki Activity Settings',
		'googleappslogin:google_sites_settings_description' => 'Set who gets to see wiki activity for the wikis you are in.  NOTE: These settings control the publish activity for everyone. Take care when making changes here.',

		'googleappslogin:google_sync_settings' => 'Google Apps Settings',
		'googleappslogin:google_sync_settings_description' => 'Manage your connection to Google Apps',

		'googleappslogin:oauth_update_interval' => 'Time interval of unread email update (in minutes)',

		'googleappslogin:sites' => 'Wikis',
		'googleappslogin:site' => 'Wiki',
		'googleappslogin:sites:your' => 'Your Wikis',
		'googleappslogin:sites:everyone' => 'All Wikis',
		'googleappslogin:sites:all' => 'All Wikis',
		'googleappslogin:site:user' => "%s's wiki",
		'googleappslogin:site:add' => 'Create new Wiki',
		'item:object:site_activity' => 'Wiki activity',
    'item:object:doc_activity' => 'Doc activity',
    'googleappslogin:doc:share:ok' => 'Document shared',
    'googleappslogin:doc:share:no_doc_id' => 'You should select a document',
    'googleappslogin:doc:share:no_comment' => 'You should add comment',
    'googleappslogin:doc:share:wrong_permissions' => 'Please, give document permissions',
		'googleappslogin:admindebugtitle' => 'Google Sites Debug',
		'googleappslogin:googlesitesreset' => 'Google Sites have been successfully reset',
		'googleappslogin:access_level' => 'Access Level'
  );

add_translation('en',$english);

?>
