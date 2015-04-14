<?php
/**
 * Googleapps helper functions
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

/**
 * Get account settings content
 */
function googleapps_get_page_content_settings_account() {
	$params['title'] = elgg_echo('googleapps:menu:google_sync_settings');
	
	$user = elgg_get_logged_in_user_entity();

	if ($user->google_connected || $user->google) {
		$vars = array(
			'id' => 'google-auth-settings-form',
			'forms' => 'google_auth_settings_form',
		);
		$params['content'] = elgg_view_form('google/auth/settings', $vars, array('user' => $user));
		$params['content'] .= elgg_view_form('google/auth/disconnect', array('class' => 'elgg-form-alt'));
	} else {
		$params['content'] = elgg_view_form('google/auth/connect');
	}
	
	$params['layout'] = 'one_sidebar';
	return $params;
}

/**
 * Get google docs listing content
 */
function googleapps_get_page_content_docs_list($container_guid = NULL) {
	elgg_load_js('elgg.googlefilepicker');
	elgg_load_js('google-js-api');
	elgg_load_js('google-doc-picker-client');
	
	$params['filter_context'] = $container_guid ? 'mine' : 'all';
	
	if ($container_guid) {
		$container = get_entity($container_guid);
		
		// Make sure container is a user or group, otherwise things will look weird
		if (!elgg_instanceof($container, 'user') && !elgg_instanceof($container, 'group')) {
			// Scram..
			forward('googleapps/docs/all');
		}
		
		if ($container != elgg_get_logged_in_user_entity() || elgg_instanceof($container, 'group')) {
			$params['filter_context'] = FALSE;
		}

	
		elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');
		elgg_push_breadcrumb($container->name);
		
		$content = elgg_list_entities(array(
			'type' => 'object', 
			'subtype' => 'shared_doc', 
			'container_guid' => $container_guid,
			'full_view' => FALSE
		));

		$params['title'] = elgg_echo('googleapps:label:user_docs', array($container->name));
	} else {
		elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');

		$content = elgg_list_entities(array(
			'type' => 'object', 
			'subtype' => 'shared_doc',
			'full_view' => FALSE
		));

		$params['title'] = elgg_echo('googleapps:menu:allshareddocs');
	}

	$owner = elgg_get_page_owner_entity();
	if (!$owner) {
		$owner = elgg_get_logged_in_user_entity();
	}

	// Only allow creating google docs if google connected and allowed to write to container
	if ($owner && $owner->canWriteToContainer() && ($owner->google_connected || $owner->google)) {
		$guid = $owner->getGUID();
		elgg_register_menu_item('title', array(
			'name' => 'googleapps_docs_add',
			'href' => "googleapps/docs/add/{$guid}",
			'text' => elgg_echo("googleapps/docs:add"),
			'link_class' => 'elgg-button elgg-button-action google-doc-picker',
		));
	}

	// If theres no content, display a nice message
	if (!$content) {
		$content = elgg_view('googleapps/noresults');
	}

	$params['context'] = 'googleapps/docs';
	$params['content'] = $content;
	return $params;
}

/**
 * Get friends docs
 */
function googleapps_get_page_content_docs_friends($user_guid) {
	$user = get_entity($user_guid);
	elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');
	elgg_push_breadcrumb($user->name, elgg_get_site_url() . 'googleapps/docs/owner/' . $user->username);
	elgg_push_breadcrumb(elgg_echo('friends'));

	if (!$friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		$content .= elgg_echo('friends:none:you');
	} else {
		$options = array(
		'type' => 'object',
		'subtype' => 'shared_doc',
		'full_view' => FALSE,
		);

		foreach ($friends as $friend) {
			$options['container_guids'][] = $friend->getGUID();
		}

		$params['title'] = elgg_echo('googleapps:menu:friendsshareddocs');

		$list = elgg_list_entities($options);
		if (!$list) {
			$content .= elgg_view('googleapps/noresults');
		} else {
			$content .= $list;
		}
	}
	$params['filter_context'] = 'friends';
	$params['context'] = 'googleapps/docs';
	$params['content'] = $content;
	return $params;
}

/**
 * Get google docs share/edit content
 *
 * @param  int $guid Document guid (for editing)
 * @return array
 */
function googleapps_get_page_content_docs_share($guid = null) {
	$google_doc = get_entity($guid);

	elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');

	if (elgg_instanceof($google_doc, 'object', 'shared_doc')) {
		elgg_push_breadcrumb($google_doc->title);
		elgg_push_breadcrumb(elgg_echo('googleapps:docs:edit'));
		$body_vars = google_doc_prepare_form_vars($google_doc);
		$title = elgg_echo('googleapps:label:editdoc', array($google_doc->title));
	} else {
		elgg_push_breadcrumb(elgg_echo('googleapps/docs:add'));
		$body_vars = array();
		$title = elgg_echo('googleapps:label:google_docs');
	}

	$params = array(
		'filter' => '',
	);
	$params['title'] = $title;
		
	// Form vars
	$vars = array();
	$vars['id'] = 'google-docs-share-form';
	$vars['name'] = 'google_docs_share_form';

	// View share form
	$params['content'] = elgg_view_form('google/docs/share', $vars, $body_vars);
		
	return $params;
}

/**
 * Get google sites/wiki content
 */
function googleapps_get_page_content_wikis_list($container_guid = NULL) {
	elgg_push_breadcrumb(elgg_echo('googleapps:menu:wikis'), elgg_get_site_url() . 'googleapps/wikis/all');
	$params['context'] = 'googleapps/wikis';
	$params['title'] = elgg_echo('googleapps:menu:wikis');
	$params['filter_context'] = $container_guid ? 'mine' : 'all';
	
	$db_prefix = elgg_get_config('dbprefix');
	
	$order = sanitize_string(get_input('order', 'ASC'));
	$order_by = sanitize_string(get_input('by', 'alpha'));

	// Default options (for 'ALL')
	$options = array(
		'type' => 'object', 
		'subtype' => 'site',
		'full_view' => FALSE,
		'limit' => 10,
	);

	if ($order_by == 'updated') {
		$options['order_by_metadata'] = array('name' => 'modified', 'as' => 'int', 'direction' => $order);
		$options['reverse_order_by'] = TRUE;
		$list = elgg_list_entities_from_metadata($options);
	} else {
		$options['joins'] = array("JOIN {$db_prefix}objects_entity oe on e.guid = oe.guid");
		$options['order_by'] = "oe.title {$order}";
		$list = elgg_list_entities($options);
	}
 
	$content = elgg_view('googleapps/wiki_sort');

	if (!$list) {
		$content .= elgg_view('googleapps/noresults');
	} else {
		$content .= $list;
	}
	
	$params['content'] = $content;
	$params['sidebar'] = elgg_view('googleapps/featured_site_sidebar');

	$domain = elgg_get_plugin_setting('google_api_domain', 'googleapps');
	$new_url = 'https://sites.google.com/a/' . $domain . '/sites/system/app/pages/meta/dashboard/create-new-site';
	
	// Show create wiki button
	if (elgg_is_logged_in()) {		
		elgg_register_menu_item('title', array(
			'name' => 'add',
			'href' => $new_url,
			'text' => elgg_echo('googleapps:menu:create_new_wiki'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	} 
	
	$params['filter'] = ' ';
	return $params;
}

/**
 * Pull together google doc variables for the edit form
 *
 * @param ElggObject       $google_doc
 * @return array
 */
function google_doc_prepare_form_vars($google_doc = NULL) {
	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'tags' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
	);

	if ($google_doc) {
		foreach (array_keys($values) as $field) {
			if (isset($google_doc->$field)) {
				$values[$field] = $google_doc->$field;
			}
		}
	}

	if (elgg_is_sticky_form('google-docs-edit-form')) {
		$sticky_values = elgg_get_sticky_values('google-docs-edit-form');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}
	
	elgg_clear_sticky_form('google-docs-edit-form');

	return $values;
}

/**
 * Prepare form vars for google calendars
 *
 * @param mixed $entity 
 * @return arary
 */
function google_calendars_prepare_form_vars($calendar = null) {
	// input names => defaults
	$values = array(
		'title' => '',
		'google_cal_feed' => '',
		'text_color' => '',
		'background_color' => '',
		'access_id' => ACCESS_DEFAULT,
		'guid' => ''
	);

	if (elgg_is_sticky_form('google-calendar-save')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('google-calendar-save', $field);
		}
	}

	elgg_clear_sticky_form('google-calendar-save');

	if (!$calendar) {
		return $values;
	}

	foreach (array_keys($values) as $field) {
		if (isset($calendar->$field)) {
			$values[$field] = $calendar->$field;
		}
	}

	$values['entity'] = $calendar;
	return $values;
}

/**
 * Retrieve and parse allowed subdomains from plugin settings
 * 
 * @return array | bool
 */
function googleapps_get_allowed_subdomains() {
	$subdomain_setting = elgg_get_plugin_setting('googleapps_subdomains', 'googleapps');
	$subdomains = preg_split('/[\.,\s]/', $subdomain_setting, -1, PREG_SPLIT_NO_EMPTY);
	if (empty($subdomains)) {
		return FALSE;
	} else {
		return $subdomains;
	}
}


/**
 * Generate google client
 * 
 * @return Google_Client
 */
function googleapps_get_client() {
	elgg_load_library('gapc:Client'); // Main client
	elgg_load_library('gapc:Plus'); // Plus
	
	// Get client id/secret from plugin settings
	$client_id = elgg_get_plugin_setting('google_api_client_id', 'googleapps');
	$client_secret = elgg_get_plugin_setting('google_api_client_secret', 'googleapps');
	$redirect_uri = elgg_get_site_url() . "googleapps/auth/callback";

	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->setScopes(array(
		'email', 
		'profile', 
		'https://sites.google.com/feeds/', 
		'https://www.googleapis.com/auth/drive',
		'https://www.googleapis.com/auth/calendar.readonly',
		'https://www.googleapis.com/auth/calendar'
	));
	$client->setAccessType('offline');
	$client->setApplicationName(elgg_get_plugin_setting('google_domain_label', 'googleapps'));
	return $client;
}

/**
 * Generate a google service account client
 */
function googleapps_get_service_client($scopes = array()) {
	elgg_load_library('gapc:Client');
	$plugin = elgg_get_plugin_from_id('googleapps');

	$client = new Google_Client();
	$client->setApplicationName(elgg_get_plugin_setting('google_domain_label', 'googleapps'));

	// Get auth/key info from plugin settings
	$key_location = elgg_get_plugin_setting('google_service_client_key', 'googleapps');
	$key_password = elgg_get_plugin_setting('google_service_client_key_password', 'googleapps');
	$service_account = elgg_get_plugin_setting('google_service_client_address', 'googleapps');
	$impersonate = elgg_get_plugin_setting('google_service_client_key_impersonate', 'googleapps');

	$key = file_get_contents($key_location);

	// Get credentials
	$credentials = new Google_Auth_AssertionCredentials(
		$service_account,
		$scopes,
		$key,
		$key_password,
		'http://oauth.net/grant_type/jwt/1.0/bearer',
		$impersonate

	);
	$client->setAssertionCredentials($credentials);

	if($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion($credentials);
	}

	return $client;
}

/**
 * Delete all site entities (debug)
 */
function googleapps_delete_all_site_entities() {
	$site_entities = elgg_get_entities(array(
		'type' => 'object', 
		'subtype' => 'site',
		'limit' => 0
	));

	foreach($site_entities as $site_entity) {
		$site_entity->delete();
	}

	return;
}

/**
 * Change the google drive file permissions based on chosen elgg permissions
 *
 * @param object $client
 * @param string $doc_id     Document id
 * @param string $access     public | domain
 * @param array  $optParams  optional params to send along with insert
 * @return bool
 */
function googleapps_update_file_permissions($client, $doc_id, $access, $optParams = array()) {
	if (empty($doc_id) || !$doc_id) {
		return FALSE;
	}

	elgg_load_library('gapc:Drive'); // Load drive lib

	$service = new Google_Service_Drive($client);

	// May insert multiple permissions
	$permissions = array();

	// Set new permission based on access requested
	if ($access == "domain") {
		$permission = new Google_Service_Drive_Permission();
		$permission->setRole('reader');

		$domain = elgg_get_plugin_setting('google_api_domain', 'googleapps');
		
		$permission->setType('domain');
		$permission->setDomain($domain);
		$permission->setValue($domain);

		$permissions[] = $permission;
	} else if ($access == 'public') {
		$permission = new Google_Service_Drive_Permission();
		$permission->setRole('reader');
		$permission->setType('anyone');
		$permission->setValue('anyone');

		$permissions[] = $permission;
	} else if (is_array($access)) {
		// Array of ElggUsers 
		foreach ($access as $user) {
			$permission = new Google_Service_Drive_Permission();
			$permission->setRole('reader');
			$permission->setType('user');
			$permission->setValue($user->email);

			$permissions[] = $permission;
		}
	}

	try {
		$success = TRUE;
		// Handle multiple permissions
		foreach ($permissions as $permission) {
			$success &= $service->permissions->insert($doc_id, $permission, $optParams);	
		}
		return $success;
	} catch (Exception $e) {
		//var_dump($e);
		return FALSE;
	}
}

/**
 * Get a single google drive file from supplied ID
 * 
 * @param object $client
 * @param string $id
 * @return Google_Service_Drive_DriveFile
 */
function googleapps_get_file_from_id($client, $id) {
	if (empty($id) || !$id) {
		return FALSE;
	}

	elgg_load_library('gapc:Drive'); // Load drive lib

	$service = new Google_Service_Drive($client);

	$file = $service->files->get($id);

	return $file;
}

/**
 * Get permissions for a single google drive file from supplied ID
 * 
 * @param object $client
 * @param string $id
 * @return array Google_Service_Drive_Permission
 */
function googleapps_get_file_permissions_from_id($client, $id) {
	if (empty($id) || !$id) {
		return FALSE;
	}

	elgg_load_library('gapc:Drive'); // Load drive lib

	$service = new Google_Service_Drive($client);

	$permissions = $service->permissions->listPermissions($id)->getItems();

	return $permissions;

}

/**
 * Create/save a shared google document
 *
 * @param Google_Service_Drive_DriveFile $document The google drive document
 * @param array                          $params   Elgg object params array:
 * 
 * 	description => null|string Document description
 *
 * 	tags => null|array Document tags
 *
 * 	access_id => null|INT Document access level
 *
 * 	container_guid => null|INT Container guid for the document
 *
 * 	entity_guid => null|INT Supply this to update an existing entity
 *
 * @return bool
 */
function googleapps_save_shared_document($document, $params = array()) {
	// Check for valid drive file
	if (!($document instanceof Google_Service_Drive_DriveFile)) {
		register_error(elgg_echo('googleapps:error:invaliddoc'));
		return FALSE;
	}

	// Supply some defaults
	$defaults = array(
		'access_id'      => ACCESS_LOGGED_IN,
		'container_guid' => elgg_get_logged_in_user_guid(),
		'entity_guid'    => FALSE
	);

	$params = array_merge($defaults, $params);

	// Check if we were supplied with an entity_guid
	if ($params['entity_guid']) {
		// Got one, check if it's a valid entity
		$shared_doc = get_entity($params['entity_guid']);
		if (!elgg_instanceof($shared_doc, 'object', 'shared_doc')) {
			register_error(elgg_echo('googleapps:error:invaliddoc'));
			return FALSE;
		}
	} else {
		// New object
		$shared_doc = new ElggObject();
		$shared_doc->subtype = 'shared_doc';
	}

	// Set document metadata from drive object
	$shared_doc->title          = $document->getTitle();
	$shared_doc->res_id         = $document->getId();
	$shared_doc->updated        = strtotime($document->getModifiedDate()); // CHECK ME
	$shared_doc->href           = $document->getAlternateLink();
	$shared_doc->icon           = $document->getIconLink();
	
	$shared_doc->description    = $params['description'];
	$shared_doc->access_id      = $params['access_id'];
	$shared_doc->container_guid	= $params['container_guid'];
	$shared_doc->tags           = string_to_tag_array($params['tags']);

	if (!$shared_doc->save()) {
		register_error(elgg_echo('googleapps:error:share_doc'));
		exit;
	}

	if (!$params['entity_guid']) {
		// Add to river
		add_to_river('river/object/shared_doc/create', 'create', elgg_get_logged_in_user_guid(), $shared_doc->guid);
	}
	
	return TRUE;
}

/**
 * Return given user's access tokens (default is logged in user)
 * 
 * @param ElggUser $user
 * @return string json_encode'd array of user access tokens
 */
function googleapps_get_user_access_tokens($user = FALSE) {
	if (!elgg_instanceof($user, 'user')) {
		$user = elgg_get_logged_in_user_entity();
	} 
	return json_encode(array(
		'access_token' => $user->google_access_token,
		'refresh_token' => $user->google_refresh_token
	));
}

/**
 * Process Google Sites 
 * - Creates new local entities 
 * - Deletes local entities not found/deleted remotley
 *
 * @return array|false
 */
function googleapps_process_sites() {
	// Don't do anything if sites are disabled
	if (elgg_get_plugin_setting('enable_google_sites', 'googleapps') == 'no') {
		return FALSE;
	}
	
	set_time_limit(0); // Long timeout, just in case

	$log .= "Processing Google Sites\n";
	$log .= "-----------------------\n";
	
	// Get service client
	$client = googleapps_get_service_client(array(
		'https://sites.google.com/feeds/'
	));

	if ($client) {
		$domain = elgg_get_plugin_setting('google_api_domain', 'googleapps');

		$base_feed = "https://sites.google.com/feeds/site/{$domain}";

		$feed_params = array(
			'alt' => 'json',
			'include-all-sites' => 'true',
			'max-results' => 500,
		);

		$feed_url = $base_feed . '?' . implode_assoc('=', '&', $feed_params);

		$log .= "Request: {$feed_url}\n";

		// Get sites feed
		$request = new Google_Http_Request($feed_url, 'GET');
		$response = $client->getAuth()->authenticatedRequest($request);

		$response = json_decode($response->getResponseBody(), TRUE);

		if ($response && is_array($response) && count($response) > 0) {
			$log .= "\nSuccess..\n\n";

			// Get exising elgg site entities
			$site_entities = elgg_get_entities(array(
				'type' => 'object', 
				'subtype' => 'site', 
				'limit' => 0
			)); 
			
			// Elgg site guid, for owner/container guid's
			$site_guid = elgg_get_site_entity()->guid;

			if (!$site_entities) {
				$site_count = 0;
			} else {
				$site_count = count($site_entities);
			}
			
			$log .= "Found {$site_count} local site(s)\n";

			// Array to compare local site id's
			$local_site_ids = array();
			
			// Array to compate remote site id's
			$remote_site_ids = array();
			
			// Build an array of remote site id's
			foreach ($response['feed']['entry'] as $site) {
				$site_id = $site['id']['$t'];
				$remote_site_ids[] = $site_id;
			}
	
			// Deleted count
			$sites_deleted = 0;
	
			// Process local sites
			foreach ($site_entities as $site) {
				$log .= "\n[{$site->title}]\n";
				$log .= "ID: {$site->site_id}\n";
				$log .= "URL: {$site->url}\n";
				$local_site_ids[] = $site->site_id;
				
				// Make sure site entity is owned by the elgg site, not a specific user
				if ($site->container_guid != $site_guid) {
					$site->container_guid = $site_guid;
					$site->owner_guid = $site_guid;
					$site->save();
					$log .= "Updated owner/container guid: {$site_guid}\n";
				}

				// Remove deleted/unavailable local sites
				if (!in_array($site->site_id, $remote_site_ids)) {
					$log .= "Site not found remotely, will be deleted.\n";
					$site->delete();
					$sites_deleted++;
				}
			}
			
			// Process all remote sites
			$log .= "\nFound " . count($response['feed']['entry']) . " remote site(s)\n";
			
			$log .= "\nRemote list:\n------------\n";
			
			// Array to hold new site
			$new_sites = array();
			
			// Process new sites
			foreach ($response['feed']['entry'] as $site) {
				$site_title = $site['title']['$t'];
				$site_id = $site['id']['$t'];

				$log .= "\n[{$site_title}]\n";
				$log .= "ID: {$site_id}\n";

				// Locate site URL and ACL feed url
				foreach($site['link'] as $link) {
					if ($link['rel'] == 'alternate') {
						$site_url = $link['href'];
					} else if ($link['rel'] == 'http://schemas.google.com/acl/2007#accessControlList') {
						$acl_url = $link['href'];
					}

					
				}

				// Build ACL feed request
				$acl_feed_params = array(
					'alt' => 'json'
				);

				$acl_feed_url = $acl_url . '?' . implode_assoc('=', '&', $acl_feed_params);

				// Get ACL feed
				$request = new Google_Http_Request($acl_feed_url, 'GET');
				$response = $client->getAuth()->authenticatedRequest($request);
				$response = json_decode($response->getResponseBody(), TRUE);
				
				// Build an array of owner emails
				$site_owners = array();
				foreach ($response['feed']['entry'] as $entry) {
					if ($entry['gAcl$role']['value'] == 'owner' && $entry['gAcl$scope']['type'] == 'user') {
						$site_owners[] = $entry['gAcl$scope']['value'];
					}
				}

				// Get activity feed (just replace feed location from the acl feed url)
				$activity_url = preg_replace('!(.*)feeds/acl/site/(.*)!', '$1feeds/activity/$2', $acl_feed_url);
				
				// Get activity feed
				$request = new Google_Http_Request($activity_url, 'GET');
				$response = $client->getAuth()->authenticatedRequest($request);
				$response = json_decode($response->getResponseBody(), TRUE);
				
				// See if there is any site activity, if so grab the latest timestamp
				$site_updated = FALSE;
				foreach ($response['feed']['entry'] as $entry) {
					$site_updated = strtotime($entry['updated']['$t']);
					break;
				}
				
				// If there was no site activity, use the main site feed updated timestamp
				if (!$site_updated) {
					$site_updated = strtotime($site['updated']['$t']);
				}

				$log .= "URL: {$site_url}\n";
										
				if (!in_array($site_id, $local_site_ids)) {
					$new_sites[$site_title] = $site_id;

					// Create new site
					$new_site = new ElggObject();
					$new_site->owner_guid = $site_guid;
					$new_site->container_guid = $site_guid;
					$new_site->site_id = $site_id;
					$new_site->title = $site_title;
					$new_site->subtype = "site";
					$new_site->url = $site_url;
					$new_site->modified = $site_updated;
					$new_site->remote_owners = $site_owners;
					$new_site->access_id = ACCESS_LOGGED_IN; // Default access, admin controlled
					//$new_site->site_access_id = ACCESS_PRIVATE ; // for site
					$new_site->save();
					
					$log .= "New site! ({$new_site->guid})\n";
					
				} else {
					$log .= "Local site exists!\n";
					// Update local site info
					$remote_site = array(
						'modified' => $site_updated,
						'owners' => $site_owners,
						'url' => $site_url,
						'title' => $site_title,
						'site_id' => $site_id
					);
					googleapps_update_local_site_info($remote_site);
				}
			}
			
			// New count
			$sites_created = count($new_sites);
			
			$log .= "\n\nCreated {$sites_created} local site(s)\n";
			$log .= "Deleted {$sites_deleted} local site(s)\n";
			
			// Get exising elgg site entities again
			$site_entities = elgg_get_entities(array(
				'type' => 'object', 
				'subtype' => 'site', 
				'limit' => 0
			));

		} else {
			$log .= "\n" . $result;
			$log .= "\nNo sites found\n";
		}
	} else {
		$log .= "Error creating client!\n";
		return FALSE;
	}

	if (elgg_in_context('googleapps_sites_log')) {
		echo "<pre>";
		echo $log;
		echo "</pre>";
	}

	return array(
		'response_list'=>$response,  
		'site_entities'=>$site_entities,
		'all_site_entities'=>$site_entities // @TODO phase this out
	);
}

/**
 * Process Google Sites Activity
 * - Creates new activity items for sites (group connected)
 * 
 * @return array|false
 */
function googleapps_process_sites_activity() {
	// Don't do anything if sites are disabled
	if (elgg_get_plugin_setting('enable_google_sites', 'googleapps') == 'no') {
		return FALSE;
	}
	
	set_time_limit(0); // Long timeout, just in case
	$log .= "Processing Google Sites Activity\n";
	$log .= "--------------------------------\n";
	
	/** GET LOCAL WIKIS THAT ARE CONNECTED TO A GROUP **/
	$options = array(
		'type' => 'object', 
		'subtype' => 'site',
		'limit' => 0,
	);

	$relationship = GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP;
	$dbprefix = elgg_get_config('dbprefix');

	// Where clause to ignore wikis that already have a relationship with another group
	$options['wheres'] = "EXISTS (
			SELECT 1 FROM {$dbprefix}entity_relationships r2 
			WHERE r2.guid_one = e.guid
			AND r2.relationship = '{$relationship}')";
			
	// Get a count 
	$options['count'] = TRUE;
	$count = elgg_get_entities($options);
	
	if ($count >= 1) {
		$log .= "Found {$count} connected site(s).\n";

		// Grab sites as a batch
		unset($options['count']);
		$sites = new ElggBatch('elgg_get_entities', $options);
		
		// Array to contain site->group relationship
		$sites_groups = array();
		
		foreach ($sites as $site) {
			$log .= "\n[{$site->title}]\n";
			$log .= "ID: {$site->site_id}\n";
			$log .= "URL: {$site->url}\n";
			
			// Get the connected group
			$options = array(
				'type' => 'group',
				'limit' => 1, // Should only be connected to one group
				'full_view' => FALSE,
				'relationship' => GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP, 
				'relationship_guid' => $site->guid, 
				'inverse_relationship' => FALSE,
			);

			$connected_groups = elgg_get_entities_from_relationship($options);
			
			$log .= "GROUP: ";
			
			if (count($connected_groups)) {
				// Store site/group
				$sites_groups[] = array('site' => $site, 'group' => $connected_groups[0]);

				$log .= $connected_groups[0]->name . " (" . $connected_groups[0]->guid . ")\n";
				$log .= "Last Activity: {$site->last_activity_time}\n";
			} else {
				$log .= "Couldn't find group! Not processing!\n";
			}
		}

		// Get service client
		$client = googleapps_get_service_client(array(
			'https://sites.google.com/feeds/'
		));
		
		if ($client) {
			$domain = elgg_get_plugin_setting('google_api_domain', 'googleapps');
			
			// Elgg site guid, for owner/container guid's
			$site_guid = elgg_get_site_entity()->guid;
			
			// Loop over our connected sites
			foreach ($sites_groups as $site_group) {

				$site = $site_group['site'];   // Site entity
	
				$group = $site_group['group']; // Group entity

				// Create feed url
				$activity_feed = preg_replace('!(.*)feeds/site/(.*)!', '$1feeds/activity/$2', $site->site_id);

				$activity_feed_params = array(
					'alt' => 'json',
					'max-results' => 500,
				);

				$activity_feed_url = $activity_feed . '?' . implode_assoc('=', '&', $activity_feed_params);

				$log .= "\nProcessing [{$site->title}]...\n\n	Request: {$activity_feed_url}\n\n";
				
				// Skip sites that have been set back to private
				if ($site->access_id == ACCESS_PRIVATE) {
					$log .= "	Site's access is PRIVATE, skipping.\n";
					continue;
				}

				// Get activity feed
				$request = new Google_Http_Request($activity_feed_url, 'GET');
				$response = $client->getAuth()->authenticatedRequest($request);
				$response = json_decode($response->getResponseBody(), TRUE);

				$last_activity_time = $site->last_activity_time;

				$site_new_activity_count = 0;
				
				// Hold each activity items updated timestamp (to store later)
				$activity_times = array();
				
				// Title for activity
				$title = "Changes on {$site->title} site";

				// Loop over activity entries
				foreach ($response['feed']['entry'] as $entry) {
					$activity_time = strtotime($entry['updated']['$t']);

					if ($last_activity_time < $activity_time) {
						// Get activity info
						$summary = $entry['summary']['$t'];

						// Parse out summary link
						preg_match_all('~<a\s+.*?</a>~is',$summary,$anchors);
						$summary_link = $anchors[0][0];
						$author_email = $entry['author'][0]['email']['$t'];
						$author_name = $entry['author'][0]['name']['$t'];

						// Store activity timestamps
						$activity_times[] = $activity_time;

						if (empty($author_email)) {
							$author_email = NULL;
							$author_output = "(unknown)";
						} else {
							// Try to find elgg user
							$users = get_user_by_email($author_email);
							if (count($users) >= 1 && elgg_instanceof($users[0], 'user')) {
								$local_user = $users[0];
								$author_output = $local_user->username;
							} else {
								$author_output = $author_email;
							}
						}

						// Use the api provided category terms to identify action type
						$category_term = $entry['category'][0]['term'];
						$category_label = $entry['category'][0]['label'];
				
						$log .= "	Found new activity! $author_output $category_label @ $activity_time\n";
					
						// Create new site activity object
						$site_activity = new ElggObject();
						$site_activity->subtype = 'site_activity';
						
						// If we have a local user for this entry, make them owner
						if ($local_user) {
							$site_activity->owner_guid = $local_user->guid;
						} else { // Site otherwise
							$site_activity->owner_guid = $group->guid;
						}
						
						// Set container guid & access to that of the group
						$site_activity->container_guid = $group->guid;
						$site_activity->access_id = $group->group_acl;
						
						// Set other data/metadata
						$site_activity->title = $title;
						$site_activity->site_name = $site->title;
						$site_activity->site_url = $site->url;
						$site_activity->author_name = $author_name;
						$site_activity->summary = $summary;           // Full 'summary' element
						$site_activity->summary_link = $summary_link;
						
						// Category term/label
						$site_activity->category_term = $category_term;
						$site_activity->category_label = $category_label;
						
						$site_activity->updated = $activity_time;
						
						// Save the site activity item
						if ($site_activity->save()) {
							$log .= "	Created new activity entity: {$site_activity->guid}\n\n";
							$site_new_activity_count++;

							$river_id = add_to_river('river/object/site_activity/create', 'create', $site_activity->owner_guid, $site_activity->guid, "", $activity_time);
							
							if ($river_id) {
								$log .= "	River entry created!\n\n";
							} else {
								$log .= "	River activity creation failed!!\n\n";
							}
						} else {
							$log .= "	Site activity creation failed!!\n\n";
						}
					}
				}

				if ($site_new_activity_count) {
					$log .= "	Created $site_new_activity_count new site_activity object(s)\n";
					
					// Update the site's last activity time
					$site->last_activity_time = max($activity_times);
				} else {
					$log .= "	No new activity.\n";
				}
			}		
		} else {
			$log .= "Error creating client!\n";
		}
	} else {
		$log .= "ABORTING: No connected sites found.\n";
	}

	if (elgg_in_context('googleapps_sites_log')) {
		echo "<pre>";
		echo $log;
		echo "</pre>";
	}
	
	return TRUE;
}

/**
 * Reset (delete) all site activity and revert site last updated times 
 * back to connection time
 */
function googleapps_reset_sites_activity() {
	$log = "Reset Site Activity\n------------------\n";

	$sites_options = array(
		'type' => 'object',
		'subtype' => 'site',
		'limit' => 0,
	);

	$sites = elgg_get_entities($sites_options);
	
	$sites_count = count($sites);

	foreach ($sites as $site) {
		$site->last_activity_time = $site->connected_time;
		$site->save();
	}

	$log .= "\nReset update time for {$sites_count} site(s)\n";

	$activity_options = array(
		'type' => 'object',
		'subtype' => 'site_activity',
		'limit' => 0,
		'count' => TRUE,
	);

	$activity_count = elgg_get_entities($activity_options);

	unset($activity_options['count']);
	
	$activity_items = elgg_get_entities($activity_options);
	
	foreach ($activity_items as $activity) {
		elgg_delete_river(array(
			'object_guid' => $activity->guid,
		));

		$activity->delete();
	}

	$log .= "\nDeleted {$activity_count} site_activity items(s)\n";

	if (elgg_in_context('googleapps_sites_log')) {
		echo "<pre>";
		echo $log;
		echo "</pre>";
	}
}

/**
 * Helper function to update a local site entities information
 * 
 * @param array $remote_site site info
 * @return mixed
 */
function googleapps_update_local_site_info($remote_site) {
	// Grab sites
	$site = elgg_get_entities_from_metadata(array(
		'type' => 'object', 
		'subtype' => 'site',
		'metadata_name' => 'site_id',
		'metadata_value' => $remote_site['site_id'],
		'limit' => 1,
	));

	// If we've got a proper site
	if (count($site) >= 1 && elgg_instanceof($site[0], 'object', 'site')) {
		$site = $site[0];
		$site->remote_owners = $remote_site['owners'];
		$site->modified = $remote_site['modified'];
		$site->url = $remote_site['url'];
		$site->title = $remote_site['title'];
		$site->save();
	}

	return FALSE;
}

/**
 * Get events from calendar api
 *
 * @param string $calendar_id Calendar ID
 * @param string $class_name  Class name for display in fullcalendar
 * @param string $start_date  Event upper limit
 * @param string $end_date    Event lower limit
 * @return array
 */
function googleapps_get_calendar_events($calendar_id, $class_name = FALSE, $start_date = FALSE, $end_date = FALSE) {
	if (!$calendar_id) {
		return FALSE;
	}

	// Get google client and load calendar API
	elgg_load_library('gapc:Client');
	elgg_load_library('gapc:Calendar');

	$client = googleapps_get_service_client(array(
		'https://www.googleapis.com/auth/calendar',
		'https://www.googleapis.com/auth/calendar.readonly'
	));

	$service = new Google_Service_Calendar($client);

	$optParams = array(
		'showDeleted' => 0,
		'maxResults' => '2500',
		'singleEvents' => 1
	);

	// Attempt to create DateTime objects with given date strings
	$start_date = $start_date ? new DateTime($start_date) : FALSE;
	$end_date =  $end_date ? new DateTime($end_date) : FALSE;

	// If we've got dates, format then ISO-8602 (2014-12-07T00:00:00Z)
	if ($start_date) {
		$optParams['timeMin'] = $start_date->format('c');
	}

	if ($end_date) {
		$optParams['timeMax'] = $end_date->format('c');
	}

	$events = $service->events->listEvents($calendar_id, $optParams);

	$event_array = array();

	foreach ($events->getItems() as $item) {
		// Determine if this is an all day event		
		$allDay = FALSE;
		if (!$item->getStart()->getDateTime()) {
			$allDay = true;
		}

		// Figure out start date
		$start_string = $item->getStart()->getDateTime() ? $item->getStart()->getDateTime() : $item->getStart()->getDate();

		// Figure out end date
		$end_string = $item->getEnd()->getDateTime() ? $item->getEnd()->getDateTime() : $item->getEnd()->getDate();

		// Format dates
		$start = new DateTime($start_string);
		$end = new DateTime($end_string);

		// Add event to event array
		$event_array[] = array(
			'id' => $item->getId(),
			'title' => $item->getSummary(),
			'url' => $item->getHtmlLink(),
			'start' => $start->format('c'),
			'end' => $end->format('c'),
			'allDay' => $allDay,
			'location' => $item->getLocation(),
			'description' => $item->getDescription(),
			'className' => $class_name,
			'editable' => false,
		);
	}

	return $event_array;
}

/**
 * Helper function to map a google site activity 'label' to a 
 * friendly river verb
 * 
 * @param $label Human readable label from sites api, ie: deletion, move, etc
 * @return string 
 */
function googleapps_get_river_verb_from_category_label($label) {
	$api_labels = array(
		'creation' => 'created',
		'deletion' => 'deleted',
		'edit' => 'edited',
		'move' => 'moved',
		'recovery' => 'recovered',
	);

	return $api_labels[$label] ? $api_labels[$label] : 'updated';
}
  
/** 
 * Joins key:value pairs by inner_glue and each pair together by outer_glue 
 * @param string $inner_glue The HTTP method (GET, POST, PUT, DELETE) 
 * @param string $outer_glue Full URL of the resource to access 
 * @param array $array Associative array of query parameters 
 * @return string Urlencoded string of query parameters 
 */  
function implode_assoc($inner_glue, $outer_glue, $array) {  
  $output = array();  
  foreach($array as $key => $item) {  
    $output[] = $key . $inner_glue . urlencode($item);  
  }  
  return implode($outer_glue, $output);  
}