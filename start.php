<?php

/**
 * Elgg googlelogin plugin
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alexander Ulitin <alexander.ulitin@flatsoft.com>
 * @copyright THINK Global School 2010
 * @link http://elgg.org/
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
	require_once 'models/functions.php';
	require_once 'models/admin_functions.php';
	
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
	elgg_extend_view('metatags', 'googleapps/scripts');

	// Extend system CSS with our own styles
	elgg_extend_view('css','googleapps/css');
	
	// Extend topbar view to add new mail icon
	elgg_extend_view('elgg_topbar/extend','googleapps/new_mail');
	
	// Register subtypes
	register_entity_type('object','site_activity', 'Site activity');
  	register_entity_type('object','doc_activity', 'Doc activity');

	// Pagesetup event handler
	register_elgg_event_handler('pagesetup','system','googleapps_pagesetup');
	
	// Login handler
	register_elgg_event_handler('login', 'user', 'googleapps_login');

	// TODO: remove this permissions hook if it turns out not to be necessary
	register_plugin_hook('permissions_check','user','googleapps_can_edit');
	register_plugin_hook('entity:icon:url','user','googleapps_icon_url');

	//register CRON hook to poll for Google Site activity
	register_plugin_hook('cron', 'fiveminute', 'googleapps_cron_fetch_data');

	// Setup main page handler
	register_page_handler('googleapps','googleapps_page_handler');

	// Add menu items if user is synced and if sites/docs are enabled
	$user = get_loggedin_user();
	if (!empty($user) && $user->google) {
		if ($oauth_sync_sites != 'no') {
			// Sync wikis enabled
			add_menu(elgg_echo('googleapps:sites'), $CONFIG->wwwroot . 'pg/googleapps/wikis/' . $user->username);	
		}
		if ($oauth_sync_docs != 'no') {
			// Share docs enabled
			add_menu(elgg_echo('googleapps:google_docs'), $CONFIG->wwwroot . 'pg/googleapps/docs/');
		}
	}
	
	// Register widgets
	add_widget_type('google_docs', elgg_echo('googleapps:google_docs'), elgg_echo('googleapps:google_docs:description'));
	
	// Register actions
	register_action('googleapps/oauth_update', true, $CONFIG->pluginspath . 'googleapps/actions/oauth_update.php');
	register_action('googleapps/login', true, $CONFIG->pluginspath . 'googleapps/actions/login.php');
	register_action('googleapps/connect', true, $CONFIG->pluginspath . 'googleapps/actions/connect.php');
	register_action('googleapps/disconnect', true, $CONFIG->pluginspath . 'googleapps/actions/disconnect.php');
	register_action('googleapps/return', true, $CONFIG->pluginspath . 'googleapps/actions/return.php');
	register_action('googleapps/return_with_connect', true, $CONFIG->pluginspath . 'googleapps/actions/return_with_connect.php');
	register_action('googleapps/save', false, $CONFIG->pluginspath . 'googleapps/actions/save.php');
	register_action('googleapps/save_user_sync_settings', false, $CONFIG->pluginspath . 'googleapps/actions/save_user_sync.php');
	register_action('googleapps/share_doc', false, $CONFIG->pluginspath . 'googleapps/actions/share_doc.php');
	register_action('googleapps/change_doc_permissions', false, $CONFIG->pluginspath . 'googleapps/actions/change_doc_permissions.php');
	register_action('googleapps/sites_reset',false, $CONFIG->pluginspath . 'googleapps/actions/sites_reset.php');
}

function googleapps_pagesetup() {

	global $CONFIG;
	
	$page_owner = elgg_get_page_owner();

	// Settings items
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:google_sites_settings'), 
								'href' => $CONFIG->wwwroot . "pg/googleapps/settings/wikiactivity"), 'settings', 'z');

	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:google_sync_settings'), 
								'href' => $CONFIG->wwwroot . "pg/googleapps/settings/account"), 'settings', 'z');	

	// Wikis
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:sites:your'), 
								'href' => $CONFIG->wwwroot . 'pg/googleapps/wikis/' . $page_owner->username), 'wikis');

	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:sites:everyone'), 
								'href' => $CONFIG->wwwroot . 'pg/googleapps/wikis'), 'wikis');
														
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:site:add'), 
								'href' => $GLOBALS['link_to_add_site']), 'wikis');
	
	// Admin
	elgg_add_submenu_item(array('text' => elgg_echo('googleapps:admindebugtitle'),
								'href'=> $CONFIG->url . "pg/googleapps/settings/debug",
								'id'=>'googlesitesdebug'),'admin', 'zzz'); // zzz puts the debug at the bottom (alphabetically)
}

/**
 * googleapps settings page handler
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function googleapps_settings_page_handler($page) {
	gatekeeper();
	global $SESSION;
	
	if (!isset($page[0])) return false;
	
	set_context('settings');
	
	switch($page[0]) {
		case 'wikiactivity':
			$form = elgg_view('googleapps/googlesites/form');
			$body = elgg_view_layout('one_column_with_sidebar', $form);
			page_draw(elgg_echo('googleapps:google_sites_settings'),$body);
		break;
		case 'account':
			$body = elgg_view('googleapps/sync_form');
			$body = elgg_view_layout('one_column_with_sidebar', $body);
			page_draw(elgg_echo('googleapps:google_sync_settings'),$body);
		break;
	}
}

/**
 * googleapps page handler
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function googleapps_page_handler($page) {
	if (isset($page[0])) {
		switch ($page[0]) {
			case 'settings':
				set_context('settings');
				// Google apps settings pages
				switch ($page[1]) {
					case 'wikiactivity':
						$form = elgg_view('googleapps/googlesites/form');
						$body = elgg_view_layout('one_column_with_sidebar', $form);
						page_draw(elgg_echo('googleapps:google_sites_settings'),$body);
					break;
					case 'debug':
						admin_gatekeeper();
						elgg_admin_add_plugin_settings_sidemenu();
						set_context('admin');
						$content = elgg_view_title(elgg_echo("googleapps:admindebugtitle"));
						$content .= elgg_view('googleapps/admin/sitesdebug_nav',array('page'=>$page));

						switch ($page[2]) {
							case "byuser" :
								$content .= list_googlesite_entities_byuser();
							break;
							case "reset":
								$content .= elgg_view('googleapps/admin/reset');
							break;
							default:
								$content .= list_googlesite_entities();
							break;
						}

						$body = elgg_view_layout('administration', array('content' => $content));
						page_draw($title, $body, 'page_shells/admin');
					break;
					default:
					case 'account':
						$body = elgg_view('googleapps/sync_form');
						$body = elgg_view_layout('one_column_with_sidebar', $body);
						page_draw(elgg_echo('googleapps:google_sync_settings'),$body);
					break;
				}
			break;
			case 'docs':
				set_context('docs');
				// Google Docs pages
				switch ($page[1]) {
			        case 'permissions':
			        	include(dirname(__FILE__) . '/docs_permissions.php');
			           	return true; 
			    	break;
					default:
				       	include(dirname(__FILE__) . '/docs.php');
			        	return true;
					break;
				}
			break;
			case 'wikis':
				set_context('wikis');
				$all = true;
				if ($page['1']) {
					$all = false;
				}
				// Google sites pages
				include(dirname(__FILE__) . '/wikis.php');
			break;
		}
	} else {
		include(dirname(__FILE__) . '/wikis.php');
		return true;
	}

	return false;
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
 * Canedit plugin hook 
 * @TODO: Might be a better way to allow access to googleapps data  
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
 * Icon plugin hook? 
 */
function googleapps_icon_url($hook_name,$entity_type, $return_value, $parameters) {

	$entity = $parameters['entity'];
	if (($entity->google == 1)) {
		if (($parameters['size'] == 'tiny') || ($parameters['size'] == 'topbar')) {
			return $entity->googleapps_icon_url_mini;
		} else {
			return $entity->googleapps_icon_url_normal;
		}
	}
}

register_elgg_event_handler('init','system','googleapps_init');