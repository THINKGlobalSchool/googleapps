<?php
/**
 * Googleapps english translation
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 *
 */

$domain_label = elgg_get_plugin_setting('google_domain_label', 'googleapps');

return array(
	// General/Built In
	'item:object:site_activity' => 'Wiki activity',
   	'item:object:shared_doc' => 'Google Shared Document',
	'item:user:googleapps' => 'googleapps users',
	'shared_doc' => 'Google Docs',
	'googleapps:googleshareddoc' => 'Google Shared Docs',
	'googleapps/docs:add' => 'Share New Google Doc',
	'googleapps:docs:edit' => 'Edit',
	'googleapps:docs:none' => 'No Shared Documents',
	'admin:google_apps:sites_settings' => 'Sites Settings',
	'admin:google_apps:sites_debug' => 'Sites Debug',
	'admin:google_apps' => 'Google Apps',

	// Menu/Submenu's
	'googleapps:menu:wiki_settings' => 'Wiki Activity Settings',
	'googleapps:menu:google_sync_settings' => 'Google Apps Settings',
	'googleapps:menu:wikis' => 'Wikis',
	'googleapps:menu:create_new_wiki' => 'Create New Wiki',
	'googleapps:menu:allshareddocs' => 'All Site Google Shared Docs', 
	'googleapps:menu:friendsshareddocs' => 'Friends\' Shared Google Docs',

	// Admin Settings
	'googleapps:admin:domain' => 'Google Apps (hosted) Domain Name (ie: thinkglobalschool.com)',
	'googleapps:admin:subdomains' => 'Allowed subdomains, comma seperated',
	'googleapps:admin:secret' => 'Secret Key (to use with OAuth)',
	'googleapps:admin:yes' => 'Yes',
	'googleapps:admin:no' => 'No',
	'googleapps:admin:enable_google_sites' => 'Enable Google Sites ',
   	'googleapps:admin:enable_google_docs' => 'Enable Google Docs',
	'googleapps:admin:loginlabel' => 'Log in with Google Text',
	'googleapps:admin:domainlabel' => 'Domain label (ie: Our Friendly Site)',
	'googleapps:admin:api_client_id' => 'Google API Client ID (Web application)',
	'googleapps:admin:api_client_secret' => 'Google API Client Secret (Web application)',
	'googleapps:admin:drive_api_key' => 'Google Drive API Key (Key for browser applicatons)',
	'googleapps:admin:authentication' => 'Authentication/Authorization Settings',
	'googleapps:admin:service' => 'Service Account Settings',
	'googleapps:admin:pluginsettings' => 'General Plugin Settings',
	'googleapps:admin:service_client_id' => 'Service Account Client ID',
	'googleapps:admin:service_address' => 'Service Account Email Address',
	'googleapps:admin:service_keylocation' => 'Service Account Key Location',
	'googleapps:admin:service_keypassword' => 'Service Account Key Password',
	'googleapps:admin:service_impersonate' => 'Service Admin Account (for admin impersonation)',

	// User settings
	'googleapps:usersettings:login_description' => 'Connect your Spot user account with your Google Apps account.',
	'googleapps:usersettings:sites_description' => 'Set each local site\'s view access access level',
	'googleapps:usersettings:sync_description' => 'Manage your connection to Google Apps',

	// Error messages
	'googleapps:error:account_create' => 'Unable to create your account. Please contact the site administrator or try again later.',
	'googleapps:error:account_inactive' => 'Account is inactive',
	'googleapps:error:banned' => 'Cannot log you in. Please contact the site administrator or try again later.',
	'googleapps:error:googlereturned' => 'Google returned the following error message: %s',
	'googleapps:error:account_duplicate' => 'Account with the same username: (%s) already exists on this site.',
	'googleapps:error:wrongdomain' => 'Cannot resolve OpenID entrypoint for your Google Apps (hosted) domain or domain is not google-hosted.',
	'googleapps:error:passworddisconnect' => 'Please provide a password before you disconnect your profile from Google Apps.',
	'googleapps:error:notauthorized' => 'Not authorized',
	'googleapps:error:nodata' => 'No data',
	'googleapps:error:noemail' => 'No email', 
	'googleapps:error:emailexists' => 'Email address: %s is already in use by another user.',
	'googleapps:error:share_doc' => 'There was an error sharing the document.',
	'googleapps:error:share_doc_save' => 'There was an error editing the document.',
	'googleapps:error:document_id_required' => 'You need to select a document first',
	'googleapps:error:invalid_url' => 'Invalid Google Document URL',
	'googleapps:error:delete' => 'There was an error deleting the shared Google Document',
	'googleapps:error:notfound' => 'Document not found',
	'googleapps:error:requiredfields' => 'One or more required fields are missing',
	'googleapps:error:nopermission' => 'You do not have permission to share this document to the group',
	'googleapps:error:invalidwiki' => 'Invalid wiki',
	'googleapps:error:invaliddoc' => 'Invalid Google Doc',
	'googleapps:error:wikiconnectionfailed' => 'Could not connect wiki to group',
	'googleapps:error:wikidisconnectionfailed' => 'Could not disconnect wiki from group',
	'googleapps:error:invalidstate' => 'Invalid Login State',
	'googleapps:error:loginerror' => 'Error: %s',
	'googleapps:error:missinglogin' => 'Missing login authentication date',
	'googleapps:error:accesstokens' => 'There was an error retrieving access tokens',
	'googleapps:error:notconnected' => 'No Google connected account found',
	'googleapps:error:existing_account' => 'It appears that email address is already registered and is not connected to Google. If you are the email owner, please log in to Spot and connect manually.',
	'googleapps:error:disconnect' => 'There was an error disconnecting your Google account',

	// Success messages
	'googleapps:success:disconnect' => 'Your profile has been successfully disconnected from Google.', 
	'googleapps:success:connect' => 'Google connected account created! Welcome to Spot %s!',
	'googleapps:success:manual_connect' => 'Google account connected!',
	'googleapps:success:sites_reset' => 'Google Sites have been successfully reset',
	'googleapps:success:delete' => 'Google Shared Document successfully deleted',
	'googleapps:success' => 'Success!',
	'googleapps:success:feature' => 'Wiki Successfully Featured',
	'googleapps:success:unfeature' => 'Wiki Successfully Unfeatured',
	'googleapps:success:groupwikiconnected' => 'Successfully connected wiki to group',
	'googleapps:success:groupwikidisconnected' => 'Successfully disconnected wiki from group',
	'googleapps:success:share_doc_save' => 'Successfully edited Google Doc',

	// General labels
	'googleapps:label:user_docs' => '%s\'s Shared Google Docs',
	'googleapps:label:user_wikis' => '%s\'s Wikis',
	'googleapps:label:google_docs' => 'Google Docs',
	'googleapps:label:google_docs_description' => '',
	'googleapps:label:site' => 'Wiki',
	'googleapps:label:access_level' => 'Access Level',
	'googleapps:label:action_required' => 'Action Required',
	'googleapps:label:permissions_warning_title' => 'Document Permissions',
	'googleapps:label:shared_by' => 'Shared by %s',
	'googleapps:label:deleteconfirm' => 'Remove Shared Doc? (This will not delete the document from Google Documents, only from Spot)',
	'googleapps:label:enableshareddoc' => 'Enable group shared google docs',
	'googleapps:label:shareadoc' => 'Share a Google Doc',
	'googleapps:label:groupdocs' => 'Group google docs',
	'googleapps:label:table_select' => 'Select',
	'googleapps:label:table_name' => 'Name',
	'googleapps:label:table_collaborators' => 'Collaborators',
	'googleapps:label:owners' => 'Owner(s)',
	'googleapps:label:updated' => 'Updated',
	'googleapps:label:table_updated' => 'Last Updated',
	'googleapps:label:tooltipname' => 'What is this?',
	'googleapps:label:viewdocument' => 'View Document',
	'googleapps:label:connect' => 'Connect Google Account',
	'googleapps:label:disconnect' => 'Disconnect Google Account',
	'googleapps:label:documentsdisplay' => 'Documents to display',
	'googleapps:label:allfolders' => 'All Folders',
	'googleapps:label:choosefolder' => 'Select Folder',
	'googleapps:label:moredocs' => 'More Shared Docs',
	'googleapps:label:listsites' => 'List Sites',
	'googleapps:label:resetsites' => 'Reset Sites',
	'googleapps:label:cronsites' => 'Test/Run Cron',
	'googleapps:label:cronsyncsites' => 'Run Sync Cron',
	'googleapps:label:crongroupsites' => 'Run Group Activity Cron',
	'googleapps:label:makefeatured' => 'Make featured',
	'googleapps:label:unfeature' => 'Unfeature',
	'googleapps:label:featuredsites' => 'Featured Wiki\'s',
	'googleapps:label:orderalpha' => 'Alphabetical',
	'googleapps:label:orderupdated' => 'Updated',
	'googleapps:label:wikisortby' => 'Sort',
	'googleapps:label:sortasc' => 'Sort Ascending &#9650;',
	'googleapps:label:sortdesc' => 'Sort Descending &#9660;',
	'googleapps:label:nextpage' => 'Next page >>',
	'googleapps:label:previouspage' => '<< Previous Page',
	'googleapps:label:loadmore' => 'Load more',
	'googleapps:label:nodocuments' => 'You don\'t have any documents to share.',
	'googleapps:label:groupwikis' => 'Group Connected Wikis',
	'googleapps:label:availablewikis' => 'Available Wikis',
	'googleapps:label:connectwiki' => 'Connect',
	'googleapps:label:disconnectwiki' => 'Disconnect',
	'googleapps:label:connectedwikis' => 'Current Connected Wikis',
	'googleapps:label:wikiconnectedto' => 'Connected to',
	'googleapps:label:groupwikis' => 'Group Wikis',
	'googleapps:label:resetsiteactivity' => 'Reset Site Activity',
	'googleapps:label:googlelogin' => 'Or, sign in automatically with..',
	'googleapps:label:editdoc' => 'Edit Google Doc: %s',
	'googleapps:label:selectfile' => 'Select File',
	'googleapps:label:change' => 'Change..',
	'googleapps:label:access_other' => 'This document is not shared publicly or shared with ' . $domain_label . '. This may result in access denied errors for viewers of this post. Choose one of the following options:',
	'googleapps:label:access_domain' => 'This document is shared only with ' . $domain_label . '. This may result in access denied errors for viewers of this post. Choose one of the following options:',
	'googleapps:label:access_unowned' => 'This document is not shared publicly or shared with ' . $domain_label . '. This may result in access denied errors for viewers of this post. Contact the document owner if you wish to update it\'s permissions.',
	'googleapps:label:insertlink' => 'Insert Google Doc link',
	'googleapps:label:syncname' => 'Sync your Google account name upon login',
	'googleapps:label:nosites' => 'No sites have been synced',
	'googleapps:label:submissionnotice' => '* Adding a Google Doc to your submission will automatically share it with the To Do owner',

	// Notifications
	'googleapps:shared_doc:subject' => 'New Google Shared Doc',
	'googleapps:shared_doc:summary' => 'New Google Shared Doc: %s',
	'googleapps:shared_doc:body' => "%s shared a google doc titled: %s\n\n%s\n\nTo view the document click here:\n%s
",

	// Tooltips
	'googleapps:tooltip:match' => 'When selecting \'Match Permissions\', only those Spot users who have already been given access to the document via Google Docs will be able to see it.',

	// Tabs
	'googleapps:tab:share_url' => 'Enter URL',
	'googleapps:tab:share_browse' => 'Browse Documents',

	// Permissions submit buttons
	'googleapps:submit:public' => 'Share publicly',
	'googleapps:submit:domain' => 'Share with '  . $domain_label,
	'googleapps:submit:ignore' => 'Ignore and continue',

	// River
	'river:create:object:shared_doc' => '%s shared a Google Document titled %s',
	'river:comment:object:shared_doc' => '%s commented on the Google Document %s',
	'river:friend:user:googleapps' => "%s is now a friend with %s",
	'river:create:object:site_activity_custom' => "%s %s %s on the %s wiki",
	
	// Emails
	'googleapps:email:created:subject' => 'Google Connected Spot Account Created',
	'googleapps:email:created:body' => 	"Hi %s,

	You have successfully created a new Spot account that is connected to your Google Account.
	
	In case you disconnect your account, or cannot login with Google for whatever reason, use the following username and password to login: 
	
	Username: %s
	Password: %s
	
	You can change your password here: %s",

	// Other plugins (hook related)
	'todo:label:addgoogledoc' => 'Google Doc'
);
