<?php
/**
 * Googleapps helper functions
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

// Throwing this in here for now
require_once(elgg_get_plugins_path() . 'googleapps/lib/GAOAuth.php');

/**
 * Get account settings content
 */
function googleapps_get_page_content_settings_account() {
	$params['title'] = elgg_echo('googleapps:menu:google_sync_settings');
	
	$user = elgg_get_logged_in_user_entity();

	if ($user->google == 1) {
		$params['content'] = "<div>" . elgg_echo('googleapps:usersettings:sync_description') . "</div><br />";
		$vars = array(
			'id' => 'google-auth-settings-form',
			'forms' => 'google_auth_settings_form',
		);
		$params['content'] .= elgg_view_form('google/auth/settings', $vars, array('user' => $user));
		$params['content'] .= elgg_view_form('google/auth/disconnect');
	} else {
		$params['content'] .= "<div>" . elgg_echo('googleapps:usersettings:login_description') . "</div><br />";
		$params['content'] .= elgg_view_form('google/auth/connect');
	}
	
	$params['layout'] = 'one_sidebar';
	return $params;
}

/**
 * Get google docs listing content
 */
function googleapps_get_page_content_docs_list($container_guid = NULL) {
	$params['filter_context'] = $container_guid ? 'mine' : 'all';
	
	if ($container_guid) {
		$container = get_entity($container_guid);
		
		// Make sure container is a user or group, otherwise things will look weird
		if (!elgg_instanceof($container, 'user') && !elgg_instanceof($container, 'group')) {
			// Scram..
			forward('googleapps/docs/all');
		}
		
		if ($container != elgg_get_logged_in_user_entity()) {
			$params['filter_context'] = FALSE;
		} else {
			elgg_register_title_button('googleapps/docs');
		}
		
		if (elgg_instanceof($container, 'group')) {
			elgg_register_title_button('googleapps/docs');
			$params['filter'] = FALSE;
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
		elgg_register_title_button('googleapps/docs');
		elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');

		$content = elgg_list_entities(array(
			'type' => 'object', 
			'subtype' => 'shared_doc',
			'full_view' => FALSE
		));

		$params['title'] = elgg_echo('googleapps:menu:allshareddocs');
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
 * Get google docs share content
 */
function googleapps_get_page_content_docs_share() {
	elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');
	elgg_push_breadcrumb(elgg_echo('googleapps/docs:add'));
	$params = array(
		'filter' => '',
	);
	$params['title'] = elgg_echo('googleapps:label:google_docs');
		
	// Form vars
	$vars = array();
	$vars['id'] = 'google-docs-share-form';
	$vars['name'] = 'google_docs_share_form';
	
	// Form body vars
	$body_vars = array();
	
	// View share form
	$params['content'] = elgg_view_form('google/docs/share', $vars);
		
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

	$domain = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
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
 * Sanitize/normalize the google doc url
 * When you load up a google doc in the browser, sometimes you'll get:
 * docs1.google.com/... or spreadsheets2.google.com/...
 *
 * Need to normalize the url to be just plain docs or spreadsheets.. or whatever
 */
function googleapps_santize_google_doc_input($string) {
	// Strip out http:// and https://, trim whitespace and '#'s
	$string = str_replace(array('http://','https://'), '', trim(strtolower($string), " #"));

	$prefix = substr($string, 0, strpos($string, '.'));
	$new_prefix = trim($prefix, '1234567890');

	$string = str_replace($prefix, $new_prefix, $string);
	return $string;
}

/**
 * Delete all site entities
 */
function googleapps_delete_all_site_entities() {
	$site_entities = elgg_get_entities(array(
		'type'=>'object', 
		'subtype'=>'site', 
		'limit'=>0
	));

	foreach($site_entities as $site_entity) {
		$site_entity->delete();
	}

	return;
}

/**
 * Functions for use OAuth
 */

function calc_access($access) {
	if ($access == 22) return 2; // public
	if ($access == 2) return 1; // logged-in
	return $access; // 0 = private site
}

/**
 * Returns the client object with the given $user access token
 *
 * @param object $user
 * @return object
 */
function get_client($user) {
	$CONSUMER_KEY = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
	$CONSUMER_SECRET = elgg_get_plugin_setting('login_secret', 'googleapps');

	$client = new OAuthClient($CONSUMER_KEY, $CONSUMER_SECRET, SIG_METHOD_HMAC);
	$client->access_token = $user->access_token;
	$client->access_secret = $user->token_secret;

	return $client;
}

/**
 * Returns the authorized client for request google data
 *
 * @param bool $ajax
 * @return object|false
 */
function authorized_client($ajax = false) {
	$CONSUMER_KEY = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
	$CONSUMER_SECRET = elgg_get_plugin_setting('login_secret', 'googleapps');

	$user = $_SESSION['user'];
	if (!empty($user->access_token)) {
		$_SESSION['access_token'] = $user->access_token;
	}
	if (!empty($user->token_secret)) {
		$_SESSION['token_secret'] = $user->token_secret;
	}

	$client = new OAuthClient($CONSUMER_KEY, $CONSUMER_SECRET, SIG_METHOD_HMAC);

	if (!empty($client->access_token)) {
		$_SESSION['access_token'] = $client->access_token;
	}
	if (!empty($client->access_secret)) {
		$_SESSION['access_secret'] = $client->access_secret;
	}

	if ($client->authorized()) {
		return $client;
	} else {
		if (!$ajax) {
			// Authorise user in google for access to data
			$url = $client->oauth_authorize();
			header('Location: ' . $url);
			exit;
		} else {
			return false;
		}
	}
}

/**
 * Returns the google data
 *
 * @param bool $ajax
 * @return object
 */
function googleapps_get_oauth_data($ajax = false) {
	$client = authorized_client($ajax);
	if ($client) {
		$x = googleapps_fetch_oauth_data($client, $ajax);
		if ($ajax) {
			return $x;
		}
	}
}

/**
 * Parse the google request data
 *
 * @todo not sure what this function needs to be now.. pretty much just handles email at this point
 * @param object $client
 * @param bool $ajax
 * @param string $scope
 * @return object|false
 */
function googleapps_fetch_oauth_data($client, $ajax = false, $scope = null) {
	set_time_limit(0); // No timeout until this is sped up

	if (!is_object($client)) {
		return false;
	}

	$all = true;

	if (!empty($scope)) {
		$scope = explode(' ', $scope);
		$all = false;
	}

	$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');

	$count = 0;
	$user = $_SESSION['user'];
	if ($oauth_sync_email != 'no' &&
	((!$all && in_array('mail', $scope)) || $all)) {
		// Get count unread messages of gmail
		$count = $client->unread_messages();
		$_SESSION['google_mail_count'] = $count;
	}

	if ($ajax) {
		$response = array();
		$response['mail_count'] = !empty($count) ? $count : 0;
		//$response['new_activity'] = !empty($is_new_activity) ? 1 : 0;
		$response['new_docs'] = 0; //!empty($is_new_docs) ? 1 : 0;
		return json_encode($response);
	}
}

/**
 * Get google docs folders for authorised client
 *
 * @param object $client
 * @return object
 */
function googleapps_get_google_docs_folders($client) {

	$feed = 'https://docs.google.com/feeds/default/private/full/-/folder';
	$result = $client->execute($feed, '3.0');
	$folders_rss = simplexml_load_string($result);

	$folders = folders_from_rss($folders_rss);

	return $folders;

}

/**
 * Change the google docs permissions based on chosen elgg permissions
 *
 * @param OAuthClient 	$client 	OAUTH client
 * @param string 		$doc_id 	Document id
 * @param mixed 		$access		Either an access_id, or an array of user's emails
 */
function googleapps_update_doc_permissions($client, $doc_id, $access) {
	// If we have a single access id
	if (!is_array($access) )  {
		switch ($access) {
			case ACCESS_PUBLIC:
				$access_type='default';
				break;
			case ACCESS_LOGGED_IN:
				$access_type='domain';
				break;
		}

		$feed = 'https://docs.google.com/feeds/default/private/full/'. $doc_id.'/acl';

		$data = "<entry xmlns=\"http://www.w3.org/2005/Atom\" xmlns:gAcl='http://schemas.google.com/acl/2007'>
				<category scheme='http://schemas.google.com/g/2005#kind'
				term='http://schemas.google.com/acl/2007#accessRule'/>
		        <gAcl:role value='reader'/> ";

		if ($access_type == "domain") {
			$domain = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
			$data .= "<gAcl:scope type=\"domain\" value=\"" . $domain . "\" />";
		} else {
			$data .= "<gAcl:scope type=\"default\"/>";
		}

		$data .= "</entry>";

		$result = $client->execute_post($feed, '3.0', null, 'POST', $data);

	} else { // Batching ACL requests

		$feed = 'https://docs.google.com/feeds/default/private/full/'. $doc_id.'/acl/batch';

		$data .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:gAcl=\'http://schemas.google.com/acl/2007\'
					xmlns:batch=\'http://schemas.google.com/gdata/batch\'>
					<category scheme=\'http://schemas.google.com/g/2005#kind\' term=\'http://schemas.google.com/acl/2007#accessRule\'/>';
			
		$data .= '<entry>
					<id>https://docs.google.com/feeds/default/private/full/'.$doc_id.'/acl/user%3A'.$user->email.'</id>
					<batch:operation type="query"/>
				</entry>';

		$i=1;
		foreach ($access as $member) {
			$data .= '<entry>
					<batch:id>'.$i.'</batch:id>
					<batch:operation type=\'insert\'/>
					<gAcl:role value=\'reader\'/>
					<gAcl:scope type=\'user\' value=\''.$member.'\'/>
					</entry>';
			$i++;
		}
		$result = $client->execute_post($feed, '3.0', null, 'POST', $data);
	}
}


/**
 * Get google docs for authorized client and folder
 *
 * @param object $client
 * @param string $folder
 * @return object
 */
function googleapps_get_google_docs($client, $folder = null, $limit = 50, $start_key = NULL) {
	$params = array('max-results' => $limit, 'expand-acl' => 'true'); 
	
	if ($start_key) {
		$params['start-key'] = $start_key;
	}

	// Get google docs feeds list
	if (empty($folder)) {
		$feed = 'https://docs.google.com/feeds/default/private/full/-/mine';
	} else {
		$feed = 'https://docs.google.com/feeds/default/private/full/' . $folder . '/contents';
	}
	$feed = $feed . '?' . implode_assoc('=', '&', $params);

	$result = $client->execute($feed, '3.0', $params);

	$rss = simplexml_load_string($result);

	$documents = array();
	
	// Get next feed link
	foreach ($rss->link as $link) {
		if ($link->attributes()->rel == 'next') {
			$next_feed = (string)$link->attributes()->href;
		}
	}
	
	if ($next_feed) {
		// Need to parse out the 'start-key' param
		$url_bits = parse_url($next_feed);
		parse_str($url_bits['query'], $query);
		$next_start_key = $query['start-key'];
	}

	// Parse entries for each google document
	foreach ($rss->entry as $item) {
		$doc = googleapps_parse_doc_from_xml_element($item);
		$documents[] = $doc;
	}
	return array(
		'list' => $documents,
		'start_key' => $next_start_key,
	);

}

/**
 * Get a single google doc from supplied ID
 * 
 * @param object $client
 * @param string $id
 * @return array
 */
function googleapps_get_doc_from_id($client, $id) {
	if (empty($id) || !$id) {
		return FALSE;
	}

	$params = array('expand-acl' => 'true'); 

	$feed = "https://docs.google.com/feeds/default/private/full/{$id}?expand-acl=true";

	$result = $client->execute($feed, '3.0', $params);

	$rss = simplexml_load_string($result);

	$doc = googleapps_parse_doc_from_xml_element($rss);

	return $doc;
}

/**
 * Parse and document array from SimpleXMLElement
 *
 * @param SimpleXMLElement $item
 * @return array
 */
function googleapps_parse_doc_from_xml_element($item) {
	if (!($item instanceof SimpleXMLElement)) {
		return FALSE;
	}

	$id = preg_replace('/https\:\/\/docs\.google\.com\/feeds\/id\/(.*)/', '$1', $item->id);
	$title = $item['title'];

	$collaborators = array();

	// Sort out collaborators
	foreach ($item->xpath('gd:feedLink') as $acl_feed) {
		// Yeah this is stupid, but xpath won't cooperate
		foreach($acl_feed->feed as $feed) {
			foreach($feed->entry as $feed_entry) {
				$user = str_replace('Document Permission - ', '', $feed_entry->title);
				$collaborators[] = $user;
			}
		}
	}

	// Could be public or everyone
	if (in_array('default', $collaborators)) {
		$collaborators = 'public'; // Public document
	} else if (in_array('everyone', $collaborators)) {
		$collaborators = 'everyone_in_domain'; // Shared with domain
	}

	$links = $item->link;
	$src = '';
	$is_folder = false;
	$type = '';

	foreach ($item->category as $category) {
		$attrs = array();
		foreach ($category->attributes() as $a => $value) {
			$attrs[$a] = $value[0];
		}
		if (!empty ($attrs['scheme']) && $attrs['scheme'] == 'http://schemas.google.com/g/2005#kind') {
			$type = preg_replace('/\ label\=\"(.*)\"/', '$1', $attrs['label']->asXML());
			$is_folder = ($type == 'folder');
		}
	}

	foreach ($item->link as $link) {
		$attrs = array();
		foreach ($link->attributes() as $a => $value) {
			$attrs[$a] = $value[0];
		}
		if (!empty ($attrs['rel']) && $attrs['rel'] == 'alternate') {
			$src = $attrs['href'];
			break;
		}
	}

	if (!empty($src)) {
		$doc['id'] = $id;
		$doc['title'] = preg_replace('/\<title\>(.*)\<\/title\>/', '$1', $item->title->asXML());
		$doc['trunc_title'] = trunc_name($doc['title']);
		$doc['href'] = preg_replace('/href=\"(.*)\"/', '$1', $src->asXML());
		$doc['type'] = $type;
		$doc['updated'] = strtotime($item->updated);
		$doc['collaborators'] = $collaborators;
		return $doc;
	} else {
		return FALSE;
	}
}

/**
 * Parse google folders from rss response
 *
 * @param string $folders
 * @return array
 */
function folders_from_rss($folders) {

	$folds = array();

	foreach ($folders->entry as $item) {
		$id = preg_replace('/http\:\/\/docs\.google\.com\/feeds\/id\/(.*)/', '$1', $item->id);
		$title = preg_replace('/\<title\>(.*)\<\/title\>/', '$1', $item->title->asXML());
		$parent_id = null;

		foreach ($item->link as $link) {
			$attrs = array();
			foreach ($link->attributes() as $a => $value) {
				$attrs[$a] = $value[0];
			}
			if ($attrs['rel'] == 'http://schemas.google.com/docs/2007#parent') {
				$parent_id = preg_replace('/http\:\/\/docs\.google\.com\/feeds\/default\/private\/full\/(.*)/', '$1', $attrs['href']);
				break;
			}
		}

		$folder = new stdClass;
		$folder->id = $id;
		$folder->title = $title;
		$folder->parent_id = $parent_id;
		$folds[$folder->id] = $folder;
	}

	return $folds;

}

/**
 * Get child folders
 *
 * @param string $parent_id
 * @param string $folders
 * @return array
 */
function child_folders($parent_id, $folders) {

	$folds = array();

	foreach ($folders as $folder) {
		if ($parent_id == $folder->parent_id) {
			$folds[] = $folder;
		}
	}

	return $folds;

}

/**
 * Get html elements <option> from folders data
 *
 * @param string $folders
 * @param string $global_folders
 * @param string $default_folder
 * @param bool $without_n
 * @return string
 */
function walk_folders($folders, $global_folders, $default_folder = '', $without_n = false) {
	foreach ($folders as $folder) {
		if (!$without_n) {
			echo '
			';
		}
		echo '<option value="' . $folder->id . '"';
		if ($default_folder == $folder->id) {
			echo ' selected';
		}
		echo '>' . echo_breadcrumbs(get_breadcrumbs($folder->id, $global_folders)) . '</option>';

		$folds = child_folders($folder->id, $global_folders);
		walk_folders($folds, $global_folders, $default_folder, $without_n);
	}
}

/**
 * Get breadcrumbs for folder
 *
 * @param string $folder_id
 * @param string $folders
 * @param string $path
 * @return string
 */
function get_breadcrumbs($folder_id, $folders, $path = null) {
	if (!$path) {
		$path = array();
	}

	foreach ($folders as $folder) {
		if ($folder_id == $folder->id) {
			$path[] = $folder->title;
			return get_breadcrumbs($folder->parent_id, $folders, $path);
			break;
		}
	}

	return $path;
}

/**
 * Shorten long names in breadcrumbs for path
 *
 * @param string $path
 * @return string
 */
function echo_breadcrumbs($path = null) {

	if (!$path) {
		return false;
	}
	$i = 0;
	$result = '';

	if (count($path) > 2) {
		$newpath = array();
		$newpath[] = $path[0];
		$newpath[] = '...';
		$newpath[] = end($path);
		$path = $newpath;
	}

	foreach ($path as $folder) {
		if ($i > 0) {
			$result = ' > ' . $result;
		}
		$result = trunc_name($folder) . $result;
		$i++;
	}

	return $result;

}

/**
 * Shorten long name
 *
 * @param string $string
 * @return string
 */
function trunc_name($string = '') {

	if (empty($string)) {
		return false;
	}

	$i = 0;
	$result = '';

	$path = explode(' ', $string);

	if (count($path) > 2) {
		if (count($path) == 3 && strlen($path[1]) < 4) {
			return $string;
		}
		$newpath = array();
		$newpath[] = $path[0];
		$newpath[] = '...';
		$newpath[] = end($path);
		$path = $newpath;

		$result = implode(' ', $path);

		return $result;
	}

	return $string;
}

function get_permission_str($collaborators) {
	if(is_array($collaborators)) {
		$collaborators = count($collaborators);
	}

	$str = '';

	switch ($collaborators) {
		case 'everyone_in_domain' :
			$str = 'Everyone in domain';
			break;
		case 'public':
			$str = 'Public';
			break;
		default:
			if($collaborators > 1) $str = ($collaborators -1) . ' collaborators'; // minus owner
			else $str='me';
			break;
	}
	return $str;
}

/**
 * Create the shared google document
 *
 * @param array 	$document 		Array reprentation of the google document
 * @param string 	$description 	Description to add
 * @param array 	$tags			Elgg tag array to add to document
 * @param mixed		$access_id		Access id, either elgg contants or 'match'
 * @return bool
 */
function share_document($document, $description, $tags, $access_id, $container_guid) {
	$shared_doc = new ElggObject();
	$shared_doc->subtype 		= "shared_doc";
	$shared_doc->title 			= $document['title'];
	$shared_doc->trunc_title 	= $document['trunc_title'];
	$shared_doc->description 	= $description;
	$shared_doc->res_id			= $document['id'];
	$shared_doc->tags			= $tags;
	$shared_doc->updated		= $document['updated'];
	$shared_doc->access_id 		= $access_id;
	$shared_doc->collaborators	= $document['collaborators'];
	$shared_doc->href			= $document['href'];
	$shared_doc->container_guid	= $container_guid;

	if (!$shared_doc->save()) {
		register_error(elgg_echo('googleapps:error:share_doc'));
		exit;
	}

	// Add to river
	add_to_river('river/object/shared_doc/create', 'create', elgg_get_logged_in_user_guid(), $shared_doc->guid);
	return true;
}

/**
 * Determine if the google doc's permissions need to be updated
 *
 * @param mixed	$collaborators	either everyone_in_domain, public, or array of email addresses
 * @param mixed $access_level	access level contants, or 'match'
 */
function check_document_permission($collaborators, $access_level) {
	if ($collaborators == 'public') {
		return true;	// Document is public on Google, don't need to make any changes
	} else if ($collaborators == 'everyone_in_domain' && $access_level != ACCESS_PUBLIC) {
		return true; 	// Document is available to domain, as long as the elgg access isn't public, we're good
	} else if ($access_level == GOOGLEAPPS_ACCESS_MATCH)  {
		return true; 	// All good, just need to create a ACL in Elgg.. nothing to see here
	} else if ($members = get_members_of_access_collection($access_level)) {
		// We've got a group ACL, check if members have permission
		$collaborators = array_flip($collaborators);
		$permission = true;
		$members_email = get_members_emails($members);
		foreach ($members_email as $member) {
			if (is_null($collaborators[$member])) {
				$permission=false;
				break;
			}
		}
		return $permission;
	} else {
		return false; // All other cases need a change in permissions
	}
}

function get_members_emails($members) {
	$members_emails = array();
	foreach ($members as $member) {
		$members_emails[]=$member['email'];
	}

	return  $members_emails;
}

function get_members_not_shared($members, $doc) {
	$collaborators = $doc['collaborators'];
	$collaborators = array_flip($collaborators);

	$members_not_shared = array();

	foreach ($members as $member) {
		if (is_null($collaborators[$member])) {
			$members_not_shared[]=$member;
		}
	}
	return $members_not_shared;
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
	if (elgg_get_plugin_setting('oauth_sync_sites', 'googleapps') == 'no') {
		return FALSE;
	}
	
	set_time_limit(0); // Long timeout, just in case

	$log .= "Processing Google Sites\n";
	$log .= "-----------------------\n";
	
	/* Build a 2-legged OAuth Client */		// @TODO should be in a function 
	$CONSUMER_KEY = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
	$CONSUMER_SECRET = elgg_get_plugin_setting('login_secret', 'googleapps');
	$ADMIN_ACCOUNT = elgg_get_plugin_setting('oauth_admin_account', 'googleapps');
	//$client = new OAuthClient($CONSUMER_KEY, $CONSUMER_SECRET, SIG_METHOD_HMAC);
	
	$params = array('max-results' => 500, 'include-all-sites' => 'true'); 
	
	$client = OAuthClient::create_2_legged_client($CONSUMER_KEY, $CONSUMER_SECRET, SIG_METHOD_HMAC, null, $ADMIN_ACCOUNT, $params);
	
	if ($client) {

		$log .= "Creating 2-legged client for: {$ADMIN_ACCOUNT}\n";

		//$params = array('max-results' => 500, 'xoauth_requestor_id' => $ADMIN_ACCOUNT, 'include-all-sites' => 'true'); 
 
		$base_feed = "https://sites.google.com/feeds/site/$CONSUMER_KEY/";

		$url = $base_feed . '?' . implode_assoc('=', '&', $client->params);

		$log .= "Request: {$url}\n";

		$result = $client->execute_without_token($url, '1.4', $client->params);

		$response_list = $client->populate_sites($result); // Site list

		if ($response_list && is_array($response_list) && count($response_list) > 0) {
			$log .= "\nSuccess..\n";
			
			// Get exising elgg site entities
			$site_entities = elgg_get_entities(array(
				'type' => 'object', 
				'subtype' => 'site', 
				'limit' => 0
			)); 
			
			// Elgg site guid, for owner/container guid's
			$site_guid = elgg_get_site_entity()->guid;
			
			$log .= "Found " . count($site_entities) . " local site(s)\n";
			
			// Array to compare local site id's
			$local_site_ids = array();
			
			// Array to compate remote site id's
			$remote_site_ids = array();
			
			// Build an array of remote site id's
			foreach ($response_list as $site) {
				$remote_site_ids[] = $site['site_id'];
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
			$log .= "\nFound " . count($response_list) . " remote site(s)\n";

			$log .= "\nRemote list:\n------------\n";
			
			// Array to hold new site
			$new_sites = array();
			
			// Process new sites
			foreach ($response_list as $site) {
				$log .= "\n[{$site['title']}]\n";
				$log .= "ID: {$site['site_id']}\n";
				$log .= "URL: {$site['url']}\n";
				
				if (!in_array($site['site_id'], $local_site_ids)) {
					$new_sites[$site['title']] = $site['site_id'];

					// Create new site
					$new_site = new ElggObject();
					$new_site->owner_guid = $site_guid;
					$new_site->container_guid = $site_guid;
					$new_site->site_id = $site['site_id'];
					$new_site->title = $site['title'];
					$new_site->subtype = "site";
					$new_site->url = $site['url'];
					$new_site->modified = $site['modified'];
					$new_site->remote_owners = $site['owners'];
					$new_site->access_id = ACCESS_PRIVATE; // Default access, admin controlled
					//$new_site->site_access_id = ACCESS_PRIVATE ; // for site
					$new_site->save();
					
					$log .= "New site! ({$new_site->guid})\n";
					
				} else {
					$log .= "Local site exists!\n";
					// Update local site info
					googleapps_update_local_site_info($site);
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
		'response_list'=>$response_list,  
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
	if (elgg_get_plugin_setting('oauth_sync_sites', 'googleapps') == 'no') {
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

		/* Build a 2-legged OAuth Client */
		$CONSUMER_KEY = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
		$CONSUMER_SECRET = elgg_get_plugin_setting('login_secret', 'googleapps');
		$ADMIN_ACCOUNT = elgg_get_plugin_setting('oauth_admin_account', 'googleapps');

		$params = array('max-results' => 500); 

		$client = OAuthClient::create_2_legged_client($CONSUMER_KEY, $CONSUMER_SECRET, SIG_METHOD_HMAC, null, $ADMIN_ACCOUNT, $params);
		
		if ($client) {
			$log .= "\nCreating 2-legged client for: {$ADMIN_ACCOUNT}\n";
			
			// Elgg site guid, for owner/container guid's
			$site_guid = elgg_get_site_entity()->guid;
			
			// Loop over our connected sites
			foreach ($sites_groups as $site_group) {
				$site = $site_group['site'];   // Site entity
	
				$group = $site_group['group']; // Group entity

				// Create feed url
				$activity_feed = preg_replace('!(.*)feeds/site/(.*)!', '$1feeds/activity/$2', $site->site_id);

				// Add params
				$url = $activity_feed . '?' . implode_assoc('=', '&', $client->params);

				$log .= "\nProcessing [{$site->title}]...\n\n	Request: {$url}\n\n";
				
				// Skip sites that have been set back to private
				if ($site->access_id == ACCESS_PRIVATE) {
					$log .= "	Site's access is PRIVATE, skipping.\n";
					continue;
				}

				// Execute request
				$result = $client->execute_without_token($url, '1.4', $client->params);

				// Objectify that feed
				$activity_xml = simplexml_load_string($result);

				$last_activity_time = $site->last_activity_time;

				$site_new_activity_count = 0;
				
				// Hold each activity items updated timestamp (to store later)
				$activity_times = array();
				
				// Title for activity
				$title = "Changes on {$site->title} site";

				// Loop over each activity entry
				foreach ($activity_xml->entry as $item) {
					$activity_time = strtotime($item->updated);
					
					// If the sites activity time is less than the activity entry
					// we need to post a new object
					if ($last_activity_time < $activity_time) {
						// Get activity info
						$summary = $item->summary->div->asXML();
						$summary_link = $item->summary->div->a->asXML();
						$author_email = @$item->author->email[0];
						$author_name = @$item->author->name[0];
						
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
						$category_term = $item->category->attributes()->term;
						$category_label = $item->category->attributes()->label;

						$log .= "	Found new activity! $author_output $category_label @ $activity_time\n";
						
						// Create new site
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
						$site_activity->summary_link = $summary_link; // Just the targeted page that was edited
						
						// Category term/label
						$site_activity->category_term = $category_term;
						$site_activity->category_label = $category_label;
						
						$site_activity->updated = $activity_time;
						
						// Save the site activity item
						if ($site_activity->save()) {
							$log .= "	Created new activity entity: {$site_activity->guid}\n\n";
							$site_new_activity_count++;
							
							if (add_to_river('river/object/site_activity/create', 'create', $site_activity->owner_guid, $site_activity->guid, "", $activity_time)) {
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
		$log .= "No connected sites found. Aborting.\n";
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