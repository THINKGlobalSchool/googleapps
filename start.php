<?php
/**
 * Elgg googlelogin plugin
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
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

	// Constants
	define('GOOGLEAPPS_ACCESS_MATCH', '-10101');
	define('GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP', 'wiki_connected_to_group');

	// Register JS
	$googleapps_js = elgg_get_simplecache_url('js', 'googleapps/googleapps');
	elgg_register_simplecache_view('js/googleapps/googleapps');
	elgg_register_js('elgg.google', $googleapps_js);
	
	// Register doc chooser JS
	$gd_js = elgg_get_simplecache_url('js', 'googleapps/docbrowser');
	elgg_register_simplecache_view('js/googleapps/docbrowser');
	elgg_register_js('elgg.googledocbrowser', $gd_js);
	
	// Register CSS for social login
	$s_css = elgg_get_simplecache_url('css', 'social_login');
	elgg_register_simplecache_view('css/social_login');
	elgg_register_css('elgg.social_login', $s_css);

	// Load JS lib, only if logged in and not in admin context
	if (elgg_is_logged_in() && !elgg_in_context('admin')) {
		elgg_load_js('elgg.google');
	}
	
	// Extend admin JS
	elgg_extend_view('js/admin', 'js/googleapps/admin');

	// Extend login view google login button
	elgg_extend_view('forms/login', 'googleapps/login');

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg','css/googleapps/css');

	// Register subtypes
	elgg_register_entity_type('object','site_activity', 'Site activity');
	elgg_register_entity_type('object','shared_doc', 'Doc activity');

	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','googleapps_pagesetup');

	// Login handler
	elgg_register_event_handler('login', 'user', 'googleapps_login');

	// Hook for site menu
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'googleapps_topbar_menu_setup', 9000);
	
	// Remove the edit link from the shared doc entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'googleapps_shared_doc_entity_menu_setup');
	
	// Remove the edit link from the shared doc entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'googleapps_wiki_entity_menu_setup', 9999);
	
	// Register profile menu hook
	if (elgg_is_logged_in()) {
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'googleapps_docs_owner_block_menu');
	}
	
	// Register river menu
	elgg_register_plugin_hook_handler('register', 'menu:river', 'googleapps_wiki_activity_menu');
	
	// Register a handler for creating shared docs
	//elgg_register_event_handler('create', 'object', 'google_apps_shared_doc_create_event_listener');

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

	// register CRON hook to poll for Google Sites
	elgg_register_plugin_hook_handler('cron', 'halfhour', 'googleapps_sites_cron_handler');

	// register CRON hook to poll for Google Sites activity
	elgg_register_plugin_hook_handler('cron', 'halfhour', 'googleapps_sites_activity_cron_handler');
	
	// Interrupt output/access view
	elgg_register_plugin_hook_handler('view', 'output/access', 'googleapps_shared_doc_output_access_handler');
	
	// Hook into walled garden public pages to allow logging in with a google account
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'googleapps_public_pages_handler');

	// Setup main page handler
	elgg_register_page_handler('googleapps','googleapps_page_handler');

	// Setup url handler for google shared docs
	elgg_register_entity_url_handler('object', 'shared_doc', 'googleapps_shared_doc_url_handler');
	
	// Setup url handler for google shared docs
	elgg_register_entity_url_handler('object', 'site', 'googleapps_site_url_handler');

	// add group profile and tool entries
	if (elgg_get_plugin_setting('oauth_sync_docs', 'googleapps') == 'yes') {
		elgg_extend_view('groups/tool_latest', 'googleapps/group_shared_documents');
		add_group_tool_option('shared_doc', elgg_echo('googleapps:label:enableshareddoc'), true);
	}

	// Add menu items if user is synced and if sites/docs are enabled
	$user = elgg_get_logged_in_user_entity();
	if (!empty($user) && $user->google) {		
		if (elgg_get_plugin_setting('oauth_sync_docs', 'googleapps') != 'no') {
			$item = new ElggMenuItem('docs', elgg_echo('googleapps:label:google_docs'), 'googleapps/docs/all');
			elgg_register_menu_item('site', $item);
		}
	}

	// Show wiki's if enabled
	if (elgg_get_plugin_setting('oauth_sync_sites', 'googleapps') != 'no') {
		$item = new ElggMenuItem('wikis', elgg_echo('googleapps:menu:wikis'), 'googleapps/wikis/all');
		elgg_register_menu_item('site', $item);
	}

	// Register widgets
	elgg_register_widget_type('google_docs', elgg_echo('googleapps:label:google_docs'), elgg_echo('googleapps:label:google_docs_description'));
	
	// Extend group options
	elgg_extend_view('groups/edit', 'forms/google/wikis/group_connect', 900);
	
	// Extend group profile fields
	elgg_extend_view('groups/profile/fields', 'googleapps/wiki_group_profile');

	// Register actions

	// Login Related (auth)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/auth";
	elgg_register_action('google/auth/oauth_update', "$action_base/oauth_update.php", 'public');
	elgg_register_action('google/auth/login', "$action_base/login.php", 'public');
	elgg_register_action('google/auth/connect', "$action_base/connect.php", 'public');
	elgg_register_action('google/auth/disconnect', "$action_base/disconnect.php", 'public');
	elgg_register_action('google/auth/return', "$action_base/return.php", 'public');
	elgg_register_action('google/auth/return_with_connect', "$action_base/return_with_connect.php", 'public');
	elgg_register_action('google/auth/settings', "$action_base/settings.php");

	// Wiki related (wiki)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/wikis";
	elgg_register_action('google/wikis/settings', "$action_base/settings.php", 'admin');
	elgg_register_action('google/wikis/featured', "$action_base/featured.php", 'admin');
	elgg_register_action('google/wikis/group_connect', "$action_base/group_connect.php");
	elgg_register_action('google/wikis/group_disconnect', "$action_base/group_disconnect.php");

	// Shared Doc related (docs)
	$action_base = elgg_get_plugins_path() . "googleapps/actions/google/docs";
	elgg_register_action('google/docs/share', "$action_base/share.php");
	elgg_register_action('google/docs/permissions', "$action_base/permissions.php");
	elgg_register_action('google/docs/delete', "$action_base/delete.php");
	elgg_register_action('google/docs/edit', "$action_base/edit.php");
}

/**	
 * Pagesetup event handler
 * 
 * @return NULL
 */
function googleapps_pagesetup() {

	$menuitems = array();

	// Settings items
	/** @TODO delete
	$menuitems[] = array(
		'name' => 'wiki_settings',
		'text' => elgg_echo('googleapps:menu:wiki_settings'),
		'href' =>  'googleapps/settings/wikiactivity',
		'contexts' => array('settings'),
		'priority' => 99999,
	);
	*/

	$menuitems[] = array(
		'name' => 'sync_settings',
		'text' => elgg_echo('googleapps:menu:google_sync_settings'),
		'href' =>  'googleapps/settings/account',
		'contexts' => array('settings'),
		'priority' => 99999,
	);

	// Register menus
	foreach($menuitems as $menuitem) {
		elgg_register_menu_item('page', ElggMenuItem::factory($menuitem));
	}

	// Admin wiki debug
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('administer', 'sites_debug', 'google_apps');
		elgg_register_admin_menu_item('administer', 'sites_settings', 'google_apps');
	}
}

/**
 * googleapps page handler
 * 
 * - Now with 300% more awesome
 *
 * Dispatches various google apps related pages
 * 
 * Settings:
 * ---------
 * Wiki Activity settings:	googleapps/settings/wikiactivity
 * Google account settings:	googleapps/settings/account
 *
 * Docs:
 * -----
 * All docs:		googleapps/docs/all
 * User's docs		googleapps/docs/owner/<username>
 * Friends docs:	googleapps/docs/friends/<username>
 * Share doc:		googleapps/docs/add/<guid>
 * Group docs		googleapps/docs/group/<guid>/owner @TODO
 * Doc Chooser:		googleapps/docs/chooser (ajax)
 * 
 * Wikis:
 * ------
 * All wikis: 		googleapps/wikis/all
 * User's wikis:	googleapps/wikis/owner/<username>
 * Friends wikis: 	googleapps/wikis/friends/<username>
 * 
 * Admin:
 * ------
 * Ajax endpoints:
 * googleapps/admin/wiki_reset
 * googleapps/admin/wiki_cron
 * 
 * 
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function googleapps_page_handler($page) {	
	// One of four: settings/docs/wikis/admin
	$sub_handler = $page[0];
	
	$page_type = $page[1];
	
	switch ($sub_handler) {
		// Settings subhandler
		case 'settings':
			gatekeeper();
			elgg_set_context('settings');
			switch ($page_type) {
				default:
				case 'account':
					$params = googleapps_get_page_content_settings_account();
					break;
			}
			break;
		// Docs subhandler
		case 'docs':
			gatekeeper();
			elgg_push_context('docs');
			switch ($page_type) {
				case 'chooser':
					echo elgg_view('forms/google/docs/chooser');
					// Need to break out of the page handler for this one (ajax)
					return true;
					break;
				case 'browser':
					$client = authorized_client(true);
					$start_key = get_input('start_key', null);
					$result = googleapps_get_google_docs($client, null, 15, $start_key);
					echo json_encode($result);
					return true;
					break;
				case 'add':
					if ($page[2]) {
						elgg_set_page_owner_guid($page[2]);
					} else {
						elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
					}
					$params = googleapps_get_page_content_docs_share();
					break;
				case 'edit':
					$params = googleapps_get_page_content_docs_edit($page[2]);
					break;
				case 'owner':
					$user = get_user_by_username($page[2]);
					elgg_set_page_owner_guid($user->guid);
					$params = googleapps_get_page_content_docs_list($user->guid);
					break;
				case 'friends':
					$user = get_user_by_username($page[2]);
					$params = googleapps_get_page_content_docs_friends($user->getGUID());
					break;
				case 'group':
					elgg_set_page_owner_guid($page[2]);
					$params = googleapps_get_page_content_docs_list($page[2]);
					break;
				case 'all':
				default:
					$params = googleapps_get_page_content_docs_list();
					break;
			}
			break;
		// Wikis subhandler
		case 'wikis':
			elgg_push_context('wikis');
			switch ($page_type) {
				case 'all':
				default:
					$params = googleapps_get_page_content_wikis_list();
					break;
			}
			break;
		case 'admin':
			admin_gatekeeper();
			if (elgg_is_admin_logged_in()) {
				switch ($page_type) {
					case 'wiki_cron':
						elgg_set_context('googleapps_sites_log');
						googleapps_process_sites();
						return TRUE;
						break;
					case 'wiki_group_cron':
						elgg_set_context('googleapps_sites_log');
						googleapps_process_sites_activity();
						return TRUE;
						break;
					case 'wiki_reset_activity':
						elgg_set_context('googleapps_sites_log');
						googleapps_reset_sites_activity();
						return TRUE;
						break;
					default: 
						forward();
				}
			} else {
				forward();
			}
			break;
		default:
			return FALSE;
	}

	$body = elgg_view_layout($params['layout'] ? $params['layout'] : 'content', $params);

	echo elgg_view_page($params['title'], $body);
	return TRUE;
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
	($oauth_sync_email != 'no')) {
		$client = authorized_client();
		googleapps_fetch_oauth_data($client, false, 'mail');
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
 * Cron handler to kick off google sites polling
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_sites_cron_handler($hook, $type, $value, $params) {
	// Ignore access
	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	// Process sites
	googleapps_process_sites();
	elgg_set_ignore_access($ia);
	return $value;
}

/**
 * Cron handler to kick off google sites activity polling
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_sites_activity_cron_handler($hook, $type, $value, $params) {
	// Ignore access
	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	// Process sites
	googleapps_process_sites_activity();
	elgg_set_ignore_access($ia);
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
						try {
							$result = add_user_to_access_collection($user[0]->getGUID(), $shared_doc_acl);
						} catch (DatabaseException $e) {
							$result = FALSE;
						}
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
function googleapps_topbar_menu_setup($hook, $type, $value, $params) {		
	
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
				'href' => "https://mail.google.com/a/$domain",
				'priority' => 999,
				'item_class' => 'google-email-container',
			);
			$value[] = ElggMenuItem::factory($options);
		}	
	}
	
	return $value;	
}

/**
 * Customize the entity menu for shared docs
 * - Removes the edit link
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_shared_doc_entity_menu_setup($hook, $type, $value, $params) {
	$entity = $params['entity'];

	// don't display edit links for google docs
	if (elgg_instanceof($entity, 'object', 'shared_doc')) {
		foreach ($value as $idx => $menu) {
			if ($menu->getName() == 'edit') {
				$menu->setHref(elgg_get_site_url() . "googleapps/docs/edit/" . $entity->guid);
			}

			if ($menu->getName() == 'access') {
				$text = $menu->getText();
				$menu->setText(elgg_get_excerpt($text, 45));
			}
		}
	}

	return $value;
}

/**
 * Customize the entity menu for wikis
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_wiki_entity_menu_setup($hook, $type, $value, $params) {
	$entity = $params['entity'];


	if (elgg_instanceof($entity, 'object', 'site')) {
		// Nuke menu
		$value = array();
		
		// Add a 'feature' menu item
		if (elgg_is_admin_logged_in()) {
			if ($entity->featured_wiki == "yes") {
				$url = "action/google/wikis/featured?guid={$entity->guid}&action_type=unfeature";
				$wording = elgg_echo("googleapps:label:unfeature");
			} else {
				$url = "action/google/wikis/featured?guid={$entity->guid}&action_type=feature";
				$wording = elgg_echo("googleapps:label:makefeatured");
			}
			$options = array(
				'name' => 'wiki_feature',
				'text' => $wording,
				'href' => $url,
				'priority' => 300,
				'section' => 'info',
				'is_action' => true
			);
			$value[] = ElggMenuItem::factory($options);
		}
		
		// Access items
		$access_text = elgg_view('output/access', array('entity' => $entity));
		$options = array(
			'name' => 'wiki_access',
			'text' => "<span>{$access_text}</span>",
			'href' => false,
			'priority' => 150,
			'section' => 'info'
		);
		$value[] = ElggMenuItem::factory($options);

		// Add a 'disconnect' menu item if viewing a list of group connected wiki's
		if (elgg_in_context('group_connected_wikis')) {
			$group = elgg_get_page_owner_entity();
			// Check for group and permissions
			if (elgg_instanceof($group, 'group') && $group->canEdit()) {
				$url = "action/google/wikis/group_disconnect?group_guid={$group->guid}&wiki_guid={$entity->guid}";
				$options = array(
					'name' => 'wiki_group_disconnect',
					'text' => elgg_echo('googleapps:label:disconnectwiki'),
					'href' => $url,
					'class' => 'elgg-button elgg-button-action',
					'priority' => 400,
					'section' => 'info',
					'is_action' => true
				);
				$value[] = ElggMenuItem::factory($options);
			}
		}
	}
	return $value;
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
 * Add google docs to the owner block menu
 *
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_docs_owner_block_menu($hook, $type, $value, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "googleapps/docs/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('googledocs', elgg_echo('shared_doc'), $url);
		$value[] = $item;
	} else {
		if ($params['entity']->shared_doc_enable != "no") {
			$url = "googleapps/docs/group/{$params['entity']->guid}/owner";
			$item = new ElggMenuItem('googledocs', elgg_echo('googleapps:label:groupdocs'), $url);
			$value[] = $item;
		}
	}

	return $value;
}

/**
 * Remove items from the river menu for wiki activity entries
 *
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_wiki_activity_menu($hook, $type, $value, $params) {
	if ($params['item']->subtype == 'site_activity') {
		$value = array();
	}
	return $value;
}

/**
 * Hook to allow output/access to display a group name
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_shared_doc_output_access_handler($hook, $type, $value, $params) {
	$entity = $params['vars']['entity'];
	if ($entity && $entity->getSubtype() == 'shared_doc') {
		$access_id = $entity->access_id;

		// Try to get the regular string
		$access_id_string = get_readable_access_level($access_id);	
		
		// If its nothing, then try to grab an acl
		if (!$access_id_string) {
			$acl = get_access_collection($access_id);
			$access_id_string = $acl->name;
		}
		
		// If we haven't got a string by now, it'll be empty..
		$value = "<span class='elgg-access'>" . $access_id_string . "</span>";
	}
	return $value;
}

/**
 * Hook into walled garden public pages to allow logging in with a google account
 * 
 * @param string $hook   Name of hook
 * @param string $type   Entity type
 * @param mixed  $value  Return value
 * @param array  $params Parameters
 * @return mixed
 */
function googleapps_public_pages_handler($hook, $type, $value, $params) {
	$value[] = 'action/google/auth/login';
	$value[] = 'action/google/auth/connect';
	$value[] = 'action/google/auth/disconnect';
	$value[] = 'action/google/auth/return';
	$value[] = 'action/google/auth/return_with_connect';

	return $value;
}

/**
 * Populates the ->getUrl() method for shared google docs
 *
 * @param ElggEntity $entity The entity to return the URL for
 * @return string request url
 */
function googleapps_shared_doc_url_handler($entity) {
	return $entity->href;
}

/**
 * Populates the ->getUrl() method for google sites/wikis
 *
 * @param ElggEntity $entity The entity to return the URL for
 * @return string request url
 */
function googleapps_site_url_handler($entity) {
	return $entity->url;
}