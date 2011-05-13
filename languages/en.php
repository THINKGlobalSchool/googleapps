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
	'shared_doc' => 'Google Docs',
	'googleapps:googleshareddoc' => 'Google Shared Docs',
	'googleapps/docs:add' => 'Share New Google Doc',

	// Menu/Submenu's
	'googleapps:menu:wiki_settings' => 'Wiki Activity Settings',
	'googleapps:menu:google_sync_settings' => 'Google Apps Settings',
	'googleapps:menu:wikisyour' => 'Your Wikis',
	'googleapps:menu:wikiseveryone' => 'All Wikis',
	'googleapps:menu:wikis' => 'Wikis',
	'googleapps:menu:create_new_wiki' => 'Create New Wiki',
	'googleapps:menu:yourshareddocs' => 'Your Shared Google Docs',
	'googleapps:menu:allshareddocs' => 'All Site Google Shared Docs', 
	'googleapps:menu:friendsshareddocs' => 'Friends\' Shared Google Docs',

	// Admin Settings
	'googleapps:admin:domain' => 'Google Apps (hosted) Domain Name (ie: thinkglobalschool.com)',
	'googleapps:admin:secret' => 'Secret Key (to use with OAuth)',
	'googleapps:admin:yes' => 'Yes',
	'googleapps:admin:no' => 'No',
	'googleapps:admin:details' => 'To use Sign in with Google Apps, you have to enter your Google Apps (hosted) domain in the field below. Also you can leave it blank to use with username@gmail.com accounts.<br />If you want to provide futher integration with Google you need to register your site with Google Apps and obtain consumer key and secret. Your site should be registered with a high level of security (with a X.509 certificate). You also should enter your Secret in the text field below.',
	'googleapps:admin:sync_email' => 'Enable Google Mail',
	'googleapps:admin:sync_sites' => 'Enable Google Sites ',
   	'googleapps:admin:sync_docs' => 'Enable Google Docs',
	'googleapps:admin:oauth_update_interval' => 'Time interval of unread email update (in minutes)',
	'admin:utilities:debug_sites' => 'Google Sites Debug',

	// User settings
	'googleapps:usersettings:login_title' => 'Google Apps Login',
	'googleapps:usersettings:login_description' => 'Connect your Spot user account with your Google Apps account.',
	'googleapps:usersettings:sites_description' => 'Set who gets to see wiki activity for the wikis you are in.  NOTE: These settings control the publish activity for everyone. Take care when making changes here.',
	'googleapps:usersettings:sync_description' => 'Manage your connection to Google Apps',

	// Error messages
	'googleapps:error:account_create' => 'Unable to create your account. Please contact the site administrator or try again later.',
	'googleapps:error:account_inactive' => 'Account is inactive',
	'googleapps:error:banned' => 'Cannot log you in. Please contact the site administrator or try again later.',
	'googleapps:error:googlereturned' => 'Google returned the following error message: %s',
	'googleapps:error:account_duplicate' => 'Account with the same username: (%s) already exists on this site.',
	'googleapps:error:wrongdomain' => 'Cannot resolve OpenID entrypoint for your Google Apps (hosted) domain or domain is not google-hosted.',
	'googleapps:error:passwordstop' => 'Please provide a password before you stop synchronizing with Google Apps.',
	'googleapps:error:passworddisconnect' => 'Please provide a password before you disconnect your profile from Google Apps.',
	'googleapps:error:notauthorized' => 'Not authorized',
	'googleapps:error:nodata' => 'No data',
	'googleapps:error:noemail' => 'No email', 
	'googleapps:error:emailexists' => 'Email address: %s is already in use by another user.',
	'googleapps:error:share_doc' => 'There was an error sharing the document.',
	'googleapps:error:document_id_required' => '<h2>Uh oh..</h2><p class="googleappsdialog_message">You need to select a document first.</p>',
   	'googleapps:error:document_permissions_update' => 'Document permissions need to be updated',
	'googleapps:error:invalid_url' => 'Invalid Google Document URL',
	'googleapps:error:delete' => 'There was an error deleting the shared Google Document',
	'googleapps:error:notfound' => 'Document not found',
	'googleapps:error:nopermission' => 'You do not have permission to share this document to the group',

	// Success messages
	'googleapps:success:disconnect' => 'Your profile has been successfully disconnected from googleapps.', 
	'googleapps:success:sites_reset' => 'Google Sites have been successfully reset',
	'googleapps:success:delete' => 'Google Shared Document successfully deleted',

	// General labels
	'googleapps:label:user_docs' => '%s\'s Shared Google Docs',
	'googleapps:label:google_docs' => 'Google Docs',
	'googleapps:label:google_docs_description' => '',
	'googleapps:label:site' => 'Wiki',
	'googleapps:label:access_level' => 'Access Level',
	'googleapps:label:loading' => 'Loading...',
	'googleapps:label:match_permissions' => 'Match permissions of Google Document?',
	'googleapps:label:action_required' => 'Action Required',
	'googleapps:label:shared_by' => 'Shared by %s',
	'googleapps:label:deleteconfirm' => 'Remove Shared Doc? (This will not delete the document from Google Documents, only from Spot)',
	'googleapps:label:enableshareddoc' => 'Enable group shared google docs',
	'googleapps:label:shareadoc' => 'Share a Google Doc',
	'googleapps:label:groupdocs' => 'Group google docs',
	'googleapps:label:table_select' => 'Select',
	'googleapps:label:table_name' => 'Name',
	'googleapps:label:table_collaborators' => 'Collaborators',
	'googleapps:label:table_updated' => 'Last Updated',
	'googleapps:label:tooltipname' => 'What is this?',
	'googleapps:label:viewdocument' => 'View Document',

	// Tooltips
	'googleapps:tooltip:match' => 'When selecting \'Match Permissions\', only those Spot users who have already been given access to the document via Google Docs will be able to see it.',

	// Tabs
	'googleapps:tab:share_url' => 'Enter URL',
	'googleapps:tab:share_browse' => 'Browse Documents',

	// Permissions submit buttons
	'googleapps:submit:grant' => 'Grant view permissons',
	'googleapps:submit:ignore' => 'Ignore and continue',
	'googleapps:submit:cancel' => 'Cancel',

	// River
	'googleapps:river:shared_doc:create' => '%s shared a Google Document titled ',
);

add_translation('en',$english);
