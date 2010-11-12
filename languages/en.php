<?php

$english = array(
		'item:user:googleapps' => 'googleapps users',
		'googleapps:title' => 'Sign in with Google Apps - settings',
		'googleapps:details' => 'To use Sign in with Google Apps, you have to enter your Google Apps (hosted) domain in the field below. Also you can leave it blank to use with username@gmail.com accounts.\nIf you want to provide futher integration with Google you need to register your site with Google Apps and obtain consumer key and secret. Your site should be registered with a high level of security (with a X.509 certificate). You also should enter your Secret in the text field below.',
		
		'googleapps:domain' => 'Google Apps (hosted) domain name without www, http://, etc (example: thinkglobalschool.com)',
		'googleapps:secret' => 'Secret (to use with OAuth)',
		'googleapps:privatekey' => 'Private RSA key (to use with OAuth)',

		'googleapps:account_create' => 'Error: Unable to create your account. '
				.'Please contact the site administrator or try again later.',
		'googleapps:inactive' => 'Error: cannot activate your Elgg account.',
		'googleapps:banned' => 'Error: cannot log you in. '
				.'Please contact the site administrator or try again later.',
		'googleapps:googleappserror' => 'Error: googleapps returned the following error message: %s',
		'googleapps:account_duplicate' => 'Error: a non-googleapps account with the same username (%s) already exists on this site.',

		'googleapps:wrongdomain' => 'Error: cannot resolve OpenID entrypoint for your GoogleApps (hosted) domain or domain is not google-hosted.',
		'googleapps:settings:yes' => 'yes',
		'googleapps:settings:no' => 'no',

		'googleapps:googleapps_user_settings_title' => 'googleapps profile',
		'googleapps:googleapps_user_settings_sync_email' => 'Synchronize with google mail.',
		'googleapps:googleapps_user_settings_sync_sites' => 'Synchronize with google sites.',
    'googleapps:googleapps_user_settings_sync_docs' => 'Synchronize with google docs.',

		'googleapps:googleapps_user_settings:save:ok' => 'Your googleapps profile preference has been saved.',
		'googleapps:googleapps_login_settings:save:ok' => 'Your googleapps screen name has been saved.',
		'googleapps:googleapps_login_title' => 'Googleapps login',
		'googleapps:googleapps_login_description' => 'Connect your Spot user account with your Google Apps account.',

		'googleapps:google_docs' => 'Share Google docs',
		'googleapps:google_docs:description' => '',

		'googleapps:google_sites_settings' => 'Wiki Activity Settings',
		'googleapps:google_sites_settings_description' => 'Set who gets to see wiki activity for the wikis you are in.  NOTE: These settings control the publish activity for everyone. Take care when making changes here.',

		'googleapps:google_sync_settings' => 'Google Apps Settings',
		'googleapps:google_sync_settings_description' => 'Manage your connection to Google Apps',

		'googleapps:oauth_update_interval' => 'Time interval of unread email update (in minutes)',

		'googleapps:sites' => 'Wikis',
		'googleapps:site' => 'Wiki',
		'googleapps:sites:your' => 'Your Wikis',
		'googleapps:sites:everyone' => 'All Wikis',
		'googleapps:sites:all' => 'All Wikis',
		'googleapps:site:user' => "%s's wiki",
		'googleapps:site:add' => 'Create new Wiki',
		'item:object:site_activity' => 'Wiki activity',
    'item:object:doc_activity' => 'Doc activity',
    'googleapps:doc:share:ok' => 'Document shared',
    'googleapps:doc:share:no_doc_id' => 'You should select a document',
    'googleapps:doc:share:no_comment' => 'You should add comment',
    'googleapps:doc:share:wrong_permissions' => 'Please, give document permissions',
		'googleapps:admindebugtitle' => 'Google Sites Debug',
		'googleapps:googlesitesreset' => 'Google Sites have been successfully reset',
		'googleapps:access_level' => 'Access Level'
  );

add_translation('en',$english);

?>
