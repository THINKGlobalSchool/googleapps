<?php

/**
 * Elgg googlelogin plugin
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

/**
 * googleapps initialisation
 *
 * These parameters are required for the event API, but we won't use them:
 *
 * @param unknown_type $event
 * @param unknown_type $object_type
 * @param unknown_type $object
 */
function googleapps_init() {
	global $CONFIG;
	
	// Includes
	require_once 'lib/functions.php';
	require_once 'lib/admin_functions.php';
	require_once 'lib/googleapps_lib.php';
	
	// Need to use SSL for google urls
	$CONFIG->sslroot = str_replace('http://','https://', $CONFIG->wwwroot);

	// Set up some urls
	$googleapps_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/login', FALSE);
	$googleappsconnect_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/connect', FALSE);
	$googleappsdisconnect_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/disconnect', FALSE);
	$oauth_update_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/oauth_update', FALSE);
	$share_doc_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/share_doc', FALSE);
	$change_doc_permissions_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/change_doc_permissions', FALSE);

	// Set to globals
	$GLOBALS['googleapps_url'] = $googleapps_url;
	$GLOBALS['googleappsconnect_url'] = $googleappsconnect_url;
	$GLOBALS['googleappsdisconnect_url'] = $googleappsdisconnect_url;
	$GLOBALS['oauth_update_url'] = $oauth_update_url;
	$GLOBALS['share_doc_url'] = $share_doc_url;
	$GLOBALS['change_doc_permissions_url'] = $change_doc_permissions_url;
	$GLOBALS['oauth_update_interval'] = get_plugin_setting('oauth_update_interval', 'googleapps');
	
	// Constants
	define('GOOGLEAPPS_ACCESS_MATCH', '-10101');

	// Get plugin settings
	$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleapps');
	$oauth_sync_sites = get_plugin_setting('oauth_sync_sites', 'googleapps');
	$oauth_sync_docs = get_plugin_setting('oauth_sync_docs', 'googleapps');

	// Get google apps domain
	$domain = get_plugin_setting('googleapps_domain', 'googleapps');
	$GLOBALS['link_to_add_site'] = 'https://sites.google.com/a/' . $domain . '/sites/system/app/pages/meta/dashboard/create-new-site" target="_blank';

	// Extend login view google login button
	elgg_extend_view('login/extend', 'googleapps/login_dropdown');
	
	// Include oauth update scripts
	elgg_extend_view('metatags', 'googleapps/oauth_scripts');

	// Extend system CSS with our own styles
	elgg_extend_view('css','googleapps/css');
		
	// Include tablesorter
	elgg_register_js(elgg_get_site_url() . "mod/googleapps/vendors/jquery.tablesorter.js", 'jquery.tablesorter');
	
	// Extend topbar view to add new mail icon
	elgg_extend_view('elgg_topbar/extend','googleapps/new_mail');
	
	// Register subtypes
	register_entity_type('object','site_activity', 'Site activity');
  	register_entity_type('object','shared_doc', 'Doc activity');

	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','googleapps_pagesetup');
	
	// Login handler
	elgg_register_event_handler('login', 'user', 'googleapps_login');
	
	// Register a handler for creating shared docs
	register_elgg_event_handler('create', 'object', 'google_apps_shared_doc_create_event_listener');

	elgg_register_plugin_hook_handler('permissions_check','user','googleapps_can_edit');
	
	elgg_register_plugin_hook_handler('entity:icon:url','user','googleapps_icon_url');
	
	// Plugin hook for write access
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'googleapps_shared_doc_write_acl_plugin_hook');
	
	// Will add groups to access dropdown for google doc sharing
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'googleapps_doc_group_plugin_hook', 999);

	//register CRON hook to poll for Google Site activity
	elgg_register_plugin_hook_handler('cron', 'fiveminute', 'googleapps_cron_fetch_data');
	
	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'googleapps_docs_profile_menu');

	// Setup main page handler
	register_page_handler('googleapps','googleapps_page_handler');
	
	// Setup url handler for google shared docs
	register_entity_url_handler('googleapps_shared_doc_url_handler','object', 'shared_doc');
	
	// add group profile and tool entries
	elgg_extend_view('groups/tool_latest', 'googleapps/group_shared_documents');
	add_group_tool_option('shared_doc', elgg_echo('googleapps:label:enableshareddoc'), true);

	// Add menu items if user is synced and if sites/docs are enabled
	$user = get_loggedin_user();
	if (!empty($user) && $user->google) {
		if ($oauth_sync_sites != 'no') {
			// Sync wikis enabled
			add_menu(elgg_echo('googleapps:menu:wikis'), $CONFIG->wwwroot . 'pg/googleapps/wikis/' . $user->username);	
		}
		if ($oauth_sync_docs != 'no') {
			// Share docs enabled
			add_menu(elgg_echo('googleapps:label:google_docs'), $CONFIG->wwwroot . 'pg/googleapps/docs/');
		}
	}
	
	// Register widgets
	add_widget_type('google_docs', elgg_echo('googleapps:label:google_docs'), elgg_echo('googleapps:label:google_docs_description'));
	
	// Register actions
	register_action('googleapps/oauth_update', true, $CONFIG->pluginspath . 'googleapps/actions/oauth_update.php');
	register_action('googleapps/login', true, $CONFIG->pluginspath . 'googleapps/actions/login.php');
	register_action('googleapps/connect', true, $CONFIG->pluginspath . 'googleapps/actions/connect.php');
	register_action('googleapps/disconnect', true, $CONFIG->pluginspath . 'googleapps/actions/disconnect.php');
	register_action('googleapps/return', true, $CONFIG->pluginspath . 'googleapps/actions/return.php');
	register_action('googleapps/return_with_connect', true, $CONFIG->pluginspath . 'googleapps/actions/return_with_connect.php');
	register_action('googleapps/save_wiki_settings', false, $CONFIG->pluginspath . 'googleapps/actions/save_wiki_settings.php');
	register_action('googleapps/save_user_sync_settings', false, $CONFIG->pluginspath . 'googleapps/actions/save_user_sync_settings.php');
	register_action('googleapps/share_doc', false, $CONFIG->pluginspath . 'googleapps/actions/share_document.php');
	register_action('googleapps/change_doc_permissions', false, $CONFIG->pluginspath . 'googleapps/actions/change_document_permissions.php');
	register_action('googleapps/sites_reset',false, $CONFIG->pluginspath . 'googleapps/actions/sites_reset.php');
	register_action('googleapps/delete_shared_document', false, $CONFIG->pluginspath . 'googleapps/actions/delete_shared_document.php');
}

function googleapps_pagesetup() {
	global $CONFIG;
	
	// Settings items
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:wiki_settings'), 
								'href' => $CONFIG->wwwroot . "pg/googleapps/settings/wikiactivity"), 'settings', 'z');

	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:google_sync_settings'), 
								'href' => $CONFIG->wwwroot . "pg/googleapps/settings/account"), 'settings', 'z');	

	// Wikis
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:wikisyour'), 
								'href' => $CONFIG->wwwroot . 'pg/googleapps/wikis/' . get_loggedin_user()->username), 'wikis');

	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:wikiseveryone'), 
								'href' => $CONFIG->wwwroot . 'pg/googleapps/wikis'), 'wikis');
														
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:create_new_wiki'), 
								'href' => $GLOBALS['link_to_add_site']), 'wikis');
	
	// Docs 
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:yourshareddocs'), 
								'href' => elgg_get_site_url() . 'pg/googleapps/docs/' . get_loggedin_user()->username), 'docs');
								
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:friendsshareddocs'), 
								'href' => elgg_get_site_url() . 'pg/googleapps/docs/friends' ), 'docs');

	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:menu:allshareddocs'), 
								'href' => elgg_get_site_url() . 'pg/googleapps/docs/' ), 'docs');

	// Admin
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:admin:debug_title'),
								'href'=> $CONFIG->url . "pg/googleapps/settings/debug",
								'id'=>'googlesitesdebug'),'admin', 'zzz'); // zzz puts the debug at the bottom (alphabetically)
}	

/**
 * googleapps page handler
 * 
 * - Now with 100% more awesome
 * 
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function googleapps_page_handler($page) {
	gatekeeper();
	if (isset($page[0])) {
		switch ($page[0]) {
			case 'settings':
				set_context('settings');
				// Google apps settings pages
				switch ($page[1]) {
					case 'wikiactivity':
						$content_info = googleapps_get_page_content_settings_wikiactivity();
					break;
					case 'debug':
						admin_gatekeeper();
						elgg_admin_add_plugin_settings_sidemenu();
						set_context('admin');
						$content_info = googleapps_get_page_content_settings_debug($page[2]);
					break;
					default:
					case 'account':
						$content_info = googleapps_get_page_content_settings_account();
					break;
				}
			break;
			case 'docs':
				set_context('docs');
				// Google Docs pages
				if (isset($page[1]) && !empty($page[1])) {
					switch ($page[1]) {
						case 'friends': 
							$content_info = googleapps_get_page_content_docs_friends(get_loggedin_userid());
						break;
						case 'share':
							// Page owner fun
							if ($container = (int) get_input('container_guid')) {
								set_page_owner($container);
							}
							$page_owner = page_owner_entity();
							if (!$page_owner) {
								$page_owner_guid = get_loggedin_userid();
								if ($page_owner_guid)
									set_page_owner($page_owner_guid);
							}
							$content_info = googleapps_get_page_content_docs_sharebox();
						break;
						case 'list_form':
							echo elgg_view('googleapps/forms/document_chooser');
							// Need to break out of the page handler for this one
							return true;
						break;
						default:
							// Should be a username if we're here.. so check, if not get outta here
							if (isset($page[1])) {
								$owner_name = $page[1];
								set_input('username', $owner_name);

								// grab the page owner
								$owner = elgg_get_page_owner();
							} else {
								set_page_owner(get_loggedin_userid());
							}
							$content_info = googleapps_get_page_content_docs($owner->getGUID());
							
						break;
					}
				} else {
					$content_info = googleapps_get_page_content_docs();
				}
			break;
			case 'wikis':
				set_context('wikis');
				$content_info = googleapps_get_page_content_wikis($page[1]);
			break;
		}
	} else {
		set_context('wikis');
		$content_info = googleapps_get_page_content_wikis($page[1]);
	}
	
	$sidebar = isset($content_info['sidebar']) ? $content_info['sidebar'] : '';

	$params = array(
		'content' => elgg_view('navigation/breadcrumbs') . $content_info['content'],
		'sidebar' => $sidebar,
	);
	$body = elgg_view_layout($content_info['layout'], $params);

	echo elgg_view_page($content_info['title'], $body, $content_info['layout'] == 'administration' ? 'page_shells/admin' : 'page_shells/default');
}


/** 
 * googleapps login event handler, triggered on login
 */ 
function googleapps_login() {
	$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleapps');
	$oauth_sync_sites = get_plugin_setting('oauth_sync_sites', 'googleapps');
	$oauth_sync_docs = get_plugin_setting('oauth_sync_docs', 'googleapps');

	$user = get_loggedin_user();
	if (!empty($user) && 
		$user->google &&
		($oauth_sync_email != 'no' || $oauth_sync_sites != 'no' || $oauth_sync_docs != 'no')) 
		{
			// Email/docs/sites syncing enabled, so grab data
			googleapps_get_oauth_data();
		}
}

/**
 * Handler to pull in groups to an access dropdown, we need this for sharing google docs
 */
function googleapps_doc_group_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// get all groups if logged in
	if (($loggedin = get_loggedin_user()) && (get_context() == 'googleapps_share_doc')) {
		$groups = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => $loggedin->getGUID(), 'inverse_relationship' => FALSE, 'limit' => 999));
		if (is_array($groups)) {
			$group_access = array();
			foreach ($groups as $group) {
				$returnvalue[$group->group_acl] = elgg_echo('groups:group') . ': ' . $group->name;
			}
		}
		unset($returnvalue[ACCESS_FRIENDS]);
		unset($returnvalue[ACCESS_PRIVATE]);
	}
	return $returnvalue;
}

/** 
 * Canedit plugin hook 
 */
function googleapps_can_edit($hook_name, $entity_type, $return_value, $parameters) {
	$entity = $parameters['entity'];
	$context = get_context();
	if ($context == 'googleapps' && $entity->google == 1) {
		// should be able to do anything with googleapps user data
		return true;
	}

	if ($context == 'googleapps_cron_job') {
		return true;
	}

	return null;
}

/** 
 * Icon plugin hook
 * Probably meant to override users profile pic if one was supplied by google apps
 * Currently not implemented.. but its a cool idea.
 */
function googleapps_icon_url($hook_name, $entity_type, $return_value, $parameters) {
	$entity = $parameters['entity'];
	if (($entity->google == 1)) {
		if (($parameters['size'] == 'tiny') || ($parameters['size'] == 'topbar')) {
			return $entity->googleapps_icon_url_mini;
		} else {
			return $entity->googleapps_icon_url_normal;
		}
	}
}

/**
 * Shared doc created, create new ACL if access is set to match doc permissions
 */
function google_apps_shared_doc_create_event_listener($event, $object_type, $object) {
	if ($object->getSubtype() == 'shared_doc' && $object->access_id == GOOGLEAPPS_ACCESS_MATCH) {
		if ($object->collaborators != 'public' && $object->collaborators != 'everyone_in_domain') { // Might be public or domain
			$shared_doc_acl = create_access_collection(elgg_echo('item:object:shared_doc') . ": " . $object->title, $object->getGUID());
			if ($shared_doc_acl) {
				$object->shared_acl = $shared_doc_acl;
				$context = get_context();
				set_context('shared_doc_acl');
				foreach ($object->collaborators as $collaborator) {
					if ($user = get_user_by_email($collaborator)) {
						$result = add_user_to_access_collection($user[0]->getGUID(), $shared_doc_acl);
					}
				}
				set_context($context);
				$object->access_id = $shared_doc_acl;
				$object->save();
			} else {
				return false;
			}
		} else {
			// Google doc has public or domain permissions, assign accordingly
			switch ($object->collaborators) {
				case 'public':
					$object->access_id = ACCESS_PUBLIC;
				break;
				case 'everyone_in_domain':
					$object->access_id = ACCESS_LOGGED_IN;
				break;
				
			}
			$object->save();
		}
	}
	return true;
}

/**
 * Return the write access for the current todo if the user has write access to it.
 */
function googleapps_shared_doc_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Only include the shared doc acl if in this context, used for the create event handler
	if (get_context() == 'shared_doc_acl') {
		// get all shared docs if logged in
		if ($loggedin = get_loggedin_user()) {
			$shared_docs = elgg_get_entities(array('types' => 'object', 'subtypes' => 'shared_doc', 'limit' => 9999));
			if (is_array($shared_docs)) {
				foreach ($shared_docs as $doc) {
					$returnvalue[$doc->shared_acl] = elgg_echo('item:object:shared_doc') . ': ' . $$doc->title;
				}
			}
		}
	}
	return $returnvalue;
}

/**
 * Add google docs to the owner block
 */
function googleapps_docs_profile_menu($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	$return_value[] = array(
		'text' => elgg_echo('googleapps:label:google_docs'),
		'href' => "pg/googleapps/docs/{$params['owner']->username}",
	);

	return $return_value;
}


/**
 * Populates the ->getUrl() method for shared google docs
 *
 * @param ElggEntity entity
 * @return string request url
 */
function googleapps_shared_doc_url_handler($entity) {
	global $CONFIG;
	
	return $entity->href;
}

register_elgg_event_handler('init','system','googleapps_init');