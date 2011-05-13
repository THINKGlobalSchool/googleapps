<?php
/**
 * Elgg googlelogin plugin
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

elgg_register_event_handler('init','system','googleapps_init');

/**
 * Init event handler
 *
 * @return NULL
 */
function googleapps_init() {
	global $CONFIG;

	// Libraries
	elgg_register_library('elgg:googleapps:helpers', elgg_get_plugins_path() . 'googleapps/lib/googleapps.php');
	elgg_load_library('elgg:googleapps:helpers');

	// Register classes
	elgg_register_classes(elgg_get_plugins_path() . 'googleapps/lib/classes');

	// Need to use SSL for google urls
	$CONFIG->sslroot = str_replace('http://','https://', elgg_get_site_url());

	// Set up some urls
	$googleapps_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/login', FALSE);
	$googleappsconnect_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/connect', FALSE);
	$googleappsdisconnect_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/disconnect', FALSE);
	$oauth_update_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/oauth_update', FALSE);
	$share_doc_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/docs/share', FALSE);
	$change_doc_permissions_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/docs/update_permissions', FALSE);

	// Set to globals
	$GLOBALS['googleapps_url'] = $googleapps_url;
	$GLOBALS['googleappsconnect_url'] = $googleappsconnect_url;
	$GLOBALS['googleappsdisconnect_url'] = $googleappsdisconnect_url;
	$GLOBALS['oauth_update_url'] = $oauth_update_url;
	$GLOBALS['share_doc_url'] = $share_doc_url;
	$GLOBALS['change_doc_permissions_url'] = $change_doc_permissions_url;
	$GLOBALS['oauth_update_interval'] = elgg_get_plugin_setting('oauth_update_interval', 'googleapps');

	// Constants
	define('GOOGLEAPPS_ACCESS_MATCH', '-10101');

	// Get plugin settings
	$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
	$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
	$oauth_sync_docs = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');

	// Get google apps domain
	$domain = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
	$GLOBALS['link_to_add_site'] = 'https://sites.google.com/a/' . $domain . '/sites/system/app/pages/meta/dashboard/create-new-site';

	// Extend login view google login button
	elgg_extend_view('login/extend', 'googleapps/login_dropdown');

	// Include oauth update scripts
	elgg_extend_view('html_head/extend', 'googleapps/oauth_scripts');

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg','css/googleapps/css');

	// Include tablesorter
	//elgg_register_js(elgg_get_site_url() . "mod/googleapps/vendors/jquery.tablesorter.js", 'jquery.tablesorter');

	// Register subtypes
	elgg_register_entity_type('object','site_activity', 'Site activity');
	elgg_register_entity_type('object','shared_doc', 'Doc activity');

	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','googleapps_pagesetup');

	// Login handler
	elgg_register_event_handler('login', 'user', 'googleapps_login');

	// Hook for site menu
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'googleapps_topbar_menu_setup', 9000);
	
	// Register a handler for creating shared docs
	elgg_register_event_handler('create', 'object', 'google_apps_shared_doc_create_event_listener');

	elgg_register_plugin_hook_handler('permissions_check','user','googleapps_can_edit');

	elgg_register_plugin_hook_handler('entity:icon:url','user','googleapps_icon_url');

	// Change heading for shared docs
	elgg_register_plugin_hook_handler('ubertags:subtype:heading', 'shared_doc', 'googleapps_subtype_heading_handler');

	// Register handler to set up an icon for google docs on the timeline
	elgg_register_plugin_hook_handler('ubertags:timeline:icon', 'shared_doc', 'googleapps_timeline_doc_icon_handler');

	// Plugin hook for write access
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'googleapps_shared_doc_write_acl_plugin_hook');

	// Will add groups to access dropdown for google doc sharing
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'googleapps_doc_group_plugin_hook', 999);

	//register CRON hook to poll for Google Site activity
	elgg_register_plugin_hook_handler('cron', 'fiveminute', 'googleapps_cron_fetch_data');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'googleapps_docs_profile_menu');

	// Setup main page handler
	elgg_register_page_handler('googleapps','googleapps_page_handler');

	// Setup url handler for google shared docs
	elgg_register_entity_url_handler('object', 'shared_doc', 'googleapps_shared_doc_url_handler');

	// add group profile and tool entries
	if (elgg_get_plugin_setting('oauth_sync_docs', 'googleapps') == 'yes') {
		elgg_extend_view('groups/tool_latest', 'googleapps/group_shared_documents');
		add_group_tool_option('shared_doc', elgg_echo('googleapps:label:enableshareddoc'), true);
	}

	// Add menu items if user is synced and if sites/docs are enabled
	$user = elgg_get_logged_in_user_entity();
	if (!empty($user) && $user->google) {
		if ($oauth_sync_sites != 'no') {
			$item = new ElggMenuItem('wikis', elgg_echo('googleapps:menu:wikis'), 'googleapps/wikis/' . $user->username);
			elgg_register_menu_item('site', $item);
		}
		if ($oauth_sync_docs != 'no') {
			$item = new ElggMenuItem('docs', elgg_echo('googleapps:label:google_docs'), 'googleapps/docs/' . $user->username);
			elgg_register_menu_item('site', $item);
		}
	}

	// Register widgets
	add_widget_type('google_docs', elgg_echo('googleapps:label:google_docs'), elgg_echo('googleapps:label:google_docs_description'));

	// Register actions

	// Login Related (auth)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/auth";
	elgg_register_action('google/auth/oauth_update', "$action_base/oauth_update.php", 'public');
	elgg_register_action('google/auth/login', "$action_base/login.php", 'public');
	elgg_register_action('google/auth/connect', "$action_base/connect.php", 'public');
	elgg_register_action('google/auth/disconnect', "$action_base/disconnect.php", 'public');
	elgg_register_action('google/auth/return', "$action_base/return.php", 'public');
	elgg_register_action('google/auth/return_with_connect', "$action_base/return_with_connect.php", 'public');
	elgg_register_action('google/auth/save_user_sync_settings', "$action_base/save_user_sync_settings.php");

	// Wiki related (wiki)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/wikis";
	elgg_register_action('google/wikis/save_wiki_settings', "$action_base/save_wiki_settings.php");
	elgg_register_action('google/wikis/reset', "$action_base/reset.php", 'admin');

	// Shared Doc related (docs)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/docs";
	elgg_register_action('google/docs/share', "$action_base/share.php");
	elgg_register_action('google/docs/update_permissions', "$action_base/update_permissions.php");
	elgg_register_action('google/docs/delete', "$action_base/delete.php");
}

/**	
 * Pagesetup event handler
 * 
 * @return NULL
 */
function googleapps_pagesetup() {

	$menuitems = array();

	// Settings items
	$menuitems[] = array(
		'name' => 'wiki_settings',
		'text' => elgg_echo('googleapps:menu:wiki_settings'),
		'href' =>  'googleapps/settings/wikiactivity',
		'contexts' => array('settings'),
		'priority' => 99999,
	);


	$menuitems[] = array(
		'name' => 'sync_settings',
		'text' => elgg_echo('googleapps:menu:google_sync_settings'),
		'href' =>  'googleapps/settings/account',
		'contexts' => array('settings'),
		'priority' => 99999,
	);


	// Wikis
	$menuitems[] = array(
		'name' => 'wikis_your',
		'text' => elgg_echo('googleapps:menu:wikisyour'),
		'href' =>  'googleapps/wikis/' . elgg_get_logged_in_user_entity()->username,
		'contexts' => array('wikis'),
		'priority' => 99997,
	);

	$menuitems[] = array(
		'name' => 'wikis_everyone',
		'text' => elgg_echo('googleapps:menu:wikiseveryone'),
		'href' =>  'googleapps/wikis',
		'contexts' => array('wikis'),
		'priority' => 99998,
	);

	$menuitems[] = array(
		'name' => 'create_wiki',
		'text' => elgg_echo('googleapps:menu:create_new_wiki'),
		'href' =>  $GLOBALS['link_to_add_site'],
		'contexts' => array('wikis'),
		'priority' => 99999,
	);

	// Docs
	$menuitems[] = array(
		'name' => 'docs_your',
		'text' => elgg_echo('googleapps:menu:yourshareddocs'),
		'href' =>  'googleapps/docs/' . elgg_get_logged_in_user_entity()->username,
		'contexts' => array('docs'),
		'priority' => 99997,
	);

	$menuitems[] = array(
		'name' => 'docs_friends',
		'text' => elgg_echo('googleapps:menu:friendsshareddocs'),
		'href' =>  'googleapps/docs/friends',
		'contexts' => array('docs'),
		'priority' => 99998,
	);

	$menuitems[] = array(
		'name' => 'docs_all',
		'text' => elgg_echo('googleapps:menu:allshareddocs'),
		'href' =>  'googleapps/docs',
		'contexts' => array('docs'),
		'priority' => 99998,
	);


	// Register menus
	foreach($menuitems as $menuitem) {
		elgg_register_menu_item('page', ElggMenuItem::factory($menuitem));
	}

	// Admin wiki debug
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('administer', 'debug_sites', 'utilities');
	}
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
				elgg_set_context('settings');
				// Google apps settings pages
				switch ($page[1]) {
					case 'wikiactivity':
						$params = googleapps_get_page_content_settings_wikiactivity();
						break;
					default:
					case 'account':
						$params = googleapps_get_page_content_settings_account();
						break;
				}
				break;
			case 'docs':
				elgg_set_context('docs');
				// Google Docs pages
				if (isset($page[1]) && !empty($page[1])) {
					switch ($page[1]) {
						case 'friends':
							$content_info = googleapps_get_page_content_docs_friends(elgg_get_logged_in_user_guid());
							break;
						case 'share':
							// Page owner fun
							if ($container = (int) get_input('container_guid')) {
								elgg_set_page_owner_guid($container);
							}
							$page_owner = elgg_get_page_owner_entity();
							if (!$page_owner) {
								$page_owner_guid = elgg_get_logged_in_user_guid();
								if ($page_owner_guid)
								elgg_set_page_owner_guid($page_owner_guid);
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
								$owner = elgg_get_elgg_get_page_owner_entity();
							} else {
								elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
							}
							$content_info = googleapps_get_page_content_docs($owner->getGUID());
								
							break;
					}
				} else {
					$content_info = googleapps_get_page_content_docs();
				}
				break;
			case 'wikis':
				elgg_set_context('wikis');
				$content_info = googleapps_get_page_content_wikis($page[1]);
				break;
		}
	} else {
		elgg_set_context('wikis');
		$content_info = googleapps_get_page_content_wikis($page[1]);
	}


	//$body = elgg_view_layout($content_info['layout'], $params);

	//echo elgg_view_page($content_info['title'], $body, $content_info['layout'] == 'administration' ? 'admin' : 'default');


	$body = elgg_view_layout($params['layout'], $params);

	echo elgg_view_page($params['title'], $body);
}


/**
 * googleapps login event handler, triggered on login
 * 
 * @return NULL
 */
function googleapps_login() {
	//ignore if this is an api call
	if (elgg_get_context()=='api') return;

	$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
	$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
	$oauth_sync_docs = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');

	$user = elgg_get_logged_in_user_entity();
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
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_doc_group_plugin_hook($hook, $type, $value, $params) {
	// get all groups if logged in
	if (($loggedin = elgg_get_logged_in_user_entity()) && (elgg_get_context() == 'googleapps_share_doc')) {
		$groups = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => $loggedin->getGUID(), 'inverse_relationship' => FALSE, 'limit' => 999));
		if (is_array($groups)) {
			$group_access = array();
			foreach ($groups as $group) {
				$value[$group->group_acl] = elgg_echo('groups:group') . ': ' . $group->name;
			}
		}
		unset($value[ACCESS_FRIENDS]);
		unset($value[ACCESS_PRIVATE]);
	}
	return $value;
}

/**
 * Canedit plugin hook
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_can_edit($hook, $type, $value, $params) {
	$entity = $params['entity'];
	$context = elgg_get_context();
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
 *
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_icon_url($hook, $type, $value, $params) {
	$entity = $params['entity'];
	if (($entity->google == 1)) {
		if (($params['size'] == 'tiny') || ($params['size'] == 'topbar')) {
			return $entity->googleapps_icon_url_mini;
		} else {
			return $entity->googleapps_icon_url_normal;
		}
	}
}

/**
 * Handler to register a timeline icon for shared docs
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_timeline_doc_icon_handler($hook, $type, $value, $params) {
	if ($type == 'shared_doc') {
		return elgg_get_site_url() . "mod/googleapps/graphics/shared_doc.gif";
	}
	return false;
}

/**
 * Handler to change the subtype heading for shared docs
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return string
 */
function googleapps_subtype_heading_handler($hook, $type, $value, $params) {
	if ($type == 'shared_doc') {
		return 'Shared Docs';
	}
}

/**
 * Shared doc created, create new ACL if access is set to match doc permissions
 * 
 * @param string     $event       Event name
 * @param string     $object_type Object type
 * @param ElggObject $object      Object acted upon
 * @return bool
 */
function google_apps_shared_doc_create_event_listener($event, $object_type, $object) {
	if ($object->getSubtype() == 'shared_doc' && $object->access_id == GOOGLEAPPS_ACCESS_MATCH) {
		if ($object->collaborators != 'public' && $object->collaborators != 'everyone_in_domain') { // Might be public or domain
			$shared_doc_acl = create_access_collection(elgg_echo('item:object:shared_doc') . ": " . $object->title, $object->getGUID());
			if ($shared_doc_acl) {
				$object->shared_acl = $shared_doc_acl;
				$context = elgg_get_context();
				elgg_set_context('shared_doc_acl');
				foreach ($object->collaborators as $collaborator) {
					if ($user = get_user_by_email($collaborator)) {
						$result = add_user_to_access_collection($user[0]->getGUID(), $shared_doc_acl);
					}
				}
				elgg_set_context($context);
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
 * Topbar menu hook handler
 * - adds the new mail icon to the topbar
 */
function googleapps_topbar_menu_setup($hook, $type, $return, $params) {		
	
	if (elgg_get_plugin_setting('oauth_sync_email', 'googleapps') != 'no') {
		$user = elgg_get_logged_in_user_entity();
		if (isset($_SESSION['google_mail_count'])) {
			$count = $_SESSION['google_mail_count'];
			$domain = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
			
			$class = "elgg-icon google-email-notifier";
			$text = "<span class='$class'></span>";

			if ($count != 0) {
				$text .= "<span class=\"messages-new\">$count</span>";
			}

			$options = array(
				'name' => 'google_email',
				'text' => $text,
				'href' =>  'todo',
				'priority' => 999,
			);
			$return[] = ElggMenuItem::factory($options);
		}	
	}
	
	return $return;	
}

/**
 * Return the write access for the current todo if the user has write access to it.
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_shared_doc_write_acl_plugin_hook($hook, $type, $value, $params) {
	// Only include the shared doc acl if in this context, used for the create event handler
	if (elgg_get_context() == 'shared_doc_acl') {
		// get all shared docs if logged in
		if ($loggedin = elgg_get_logged_in_user_entity()) {
			$shared_docs = elgg_get_entities(array('types' => 'object', 'subtypes' => 'shared_doc', 'limit' => 9999));
			if (is_array($shared_docs)) {
				foreach ($shared_docs as $doc) {
					$value[$doc->shared_acl] = elgg_echo('item:object:shared_doc') . ': ' . $$doc->title;
				}
			}
		}
	}
	return $value;
}

/**
 * Add google docs to the owner block
 *
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_docs_profile_menu($hook, $type, $value, $params) {
	$value[] = array(
		'text' => elgg_echo('googleapps:label:google_docs'),
		'href' => "googleapps/docs/{$params['owner']->username}",
	);

	return $value;
}


/**
 * Populates the ->getUrl() method for shared google docs
 *
 * @param ElggEntity $entity The entity to return the URL for
 * @return string request url
 */
function googleapps_shared_doc_url_handler($entity) {
	global $CONFIG;

	return $entity->href;
}
