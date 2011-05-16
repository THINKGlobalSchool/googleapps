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
require_once(elgg_get_plugins_path() . 'googleapps/lib/OAuth.php');

/**
 * Get wiki activity settings content
 *
 * @return array
 */
function googleapps_get_page_content_settings_wikiactivity() {
	$params['title'] = elgg_echo('googleapps:menu:wiki_settings');
	$params['content'] = elgg_view('googleapps/forms/wiki_settings');
	$params['layout'] = 'one_sidebar';
	return $params;
}

/**
 * Get account settings content
 */
function googleapps_get_page_content_settings_account() {
	$params['title'] = elgg_echo('googleapps:menu:google_sync_settings');
	$params['content'] = elgg_view('googleapps/forms/sync_form');
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
		}
		
		if (elgg_instanceof($container, 'group')) {
			$params['filter'] = FALSE;
		}
		elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');
		elgg_push_breadcrumb($container->name);
		
		$content = elgg_list_entities(array('type' => 'object', 'subtype' => 'shared_doc', 'container_guid' => $container_guid));
		$params['title'] = elgg_echo('googleapps:label:user_docs', array($container->name));
	} else {
		elgg_push_breadcrumb(elgg_echo('googleapps:googleshareddoc'), elgg_get_site_url() . 'googleapps/docs/all');
		$content = elgg_list_entities(array('type' => 'object', 'subtype' => 'shared_doc'));
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
		'buttons' => '',
		'filter' => '',
	);
	$params['title'] = elgg_echo('googleapps:label:google_docs');
	$params['content'] = elgg_view('googleapps/forms/share_document');
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
	
	// Default options (for 'ALL')
	$options = array(
		'type' => 'object', 
		'subtype' => 'site',
		'full_view' => FALSE,
		'limit' => 10,
	);
	
	
	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'user')) {
		$params['title'] = elgg_echo('googleapps:label:user_wikis', array($container->name));
		elgg_push_breadcrumb($container->name);
		/* 
			This use to work like this:
			
			$res = googleapps_sync_sites(true, $container);
			$sites = $res['site_entities'];
			$params['content'] = elgg_view('googleapps/wiki_list', array('wikis' => $sites));
			
			* Note: that wiki_list view was god-awful, its long gone. 
			  It was just a weird way of listing wikis with an icon. I've remedied 
			  the issue with a proper site object view
		*/
		
		// Now we'll do the same (update sites) then list it the elggy way
		googleapps_sync_sites(true, $container);
		
		if ($container != elgg_get_logged_in_user_entity()) {
			$params['filter_context'] = FALSE;
		}
		
		// Should be enough to set the container guid here.. otherwise see above
		$options['container_guid'] = $container_guid;
	} 
	
	$list = elgg_list_entities($options);
	if (!$list) {
		$content .= elgg_view('googleapps/noresults');
	} else {
		$content .= $list;
	}
	
	$params['content'] = $content;
	
	// Build custom tabs
	if (elgg_is_logged_in()) {
		$username = elgg_get_logged_in_user_entity()->username;
		$tabs = array(
			'all' => array(
				'text' => elgg_echo('all'),
				'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "{$params['context']}/all",
				'selected' => ($params['filter_context'] == 'all'),
				'priority' => 200,
			),
			'mine' => array(
				'text' => elgg_echo('mine'),
				'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "{$params['context']}/owner/$username",
				'selected' => ($params['filter_context'] == 'mine'),
				'priority' => 300,
			)
		);

		foreach ($tabs as $name => $tab) {
			$tab['name'] = $name;
			elgg_register_menu_item('wiki_filter', $tab);
		}
	}
	
	$params['buttons'] = FALSE;
	$params['filter'] = elgg_view_menu('wiki_filter', array(
		'sort_by' => 'priority',
		// recycle the menu filter css
		'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
	));
	return $params;
}

/**
 * Update site entity access access
 */
function googleapps_update_site_entity_access($entity_id, $access) {
	$context = elgg_get_context();
	elgg_set_context('googleapps_cron_job');

	$user_site_entities = unserialize($_SESSION['user_site_entities']);

	foreach ($user_site_entities as $entity) {
		if ($entity->guid == $entity_id ) {
			$entity->site_access_id = $access;
			$entity->save();
		}
	}
	elgg_set_context($context);
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
 * List site entities
 */
function googleapps_list_sites() {
	$output = "";

	$options = array(
		'type'=>'object', 
		'subtype'=>'site', 
		'limit'=> 0,
		'count' => TRUE,
	);

	$site_count = elgg_get_entities($options);

	$options['count'] = FALSE;

	$site_entities = elgg_get_entities($options);


	$output .= "<p>Site entities found: {$site_count}</p>";
	if ($site_entities) {
		foreach($site_entities as $site_entity) {
			$output .= elgg_view('googleapps/admin/site_entity',array('site_entity'=>$site_entity));
			$output .= "<br/>";
		}
	}

	return $output;
}

/**
 * List site entities by user
 */
function googleapps_list_sites_by_user() {
	$output = "";

	$googleusers = find_metadata('googleapps_controlled_profile', 'yes', 'user', '', 999);
	foreach($googleusers as $googleuser) {
		$user = get_user($googleuser->owner_guid);
		$site_list = empty($user->site_list) ? array() : unserialize($user->site_list);
		$site_count = count($site_list);

		$output .= "<p>Sites found for <strong>{$user->name} ({$site_count})</strong>:";
		foreach($site_list as $key => $site) {
			$site_entity = get_entity($site['entity_id']);
			$output .= elgg_view('googleapps/admin/site_entity',array('site_entity'=>$site_entity));
		}
		$output .= "<hr />";
	}
	return $output;
}

/**
 * Delete all site entities
 */
function googleapps_delete_all_site_entities() {
	$site_entities = elgg_get_entities(array('type'=>'object', 'subtype'=>'site', 'limit'=>99999));
	foreach($site_entities as $site_entity) {
		$site_entity->delete();
	}
	return;
}

/**
 * Reset all user sites
 */
function googleapps_reset_user_sites() {
	$googleusers = find_metadata('googleapps_controlled_profile', 'yes', 'user', '', 999);
	foreach($googleusers as $googleuser) {
		$user = get_user($googleuser->owner_guid);
		$user->site_list = NULL;
	}
	return;
}

/**
 * Reset sites and user sites
 */
function googleapps_reset_sites() {
	googleapps_delete_all_site_entities();
	googleapps_reset_user_sites();
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
 * Save new google sites and site activity for googleapps users
 *
 * @return object
 */
function googleapps_cron_fetch_data() {

	$context = elgg_get_context();
	elgg_set_context('googleapps_cron_job');


	/* need to sync sites ? */
	$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
	if ($oauth_sync_sites == 'no') return;

	set_time_limit(0);
	$a_time=time();

	/* find all users with googleapps controlled profile */
	$result = find_metadata('googleapps_controlled_profile', 'yes', 'user', '', 999);
	if (empty($result)) {
		return;
	}

	foreach ($result as $gapps_user) {
		$user = get_user($gapps_user->owner_guid);
		if (empty($user->access_token) || empty($user->token_secret)) {
			echo 'No access token for ' . $user->username . '\n';
			continue;
		}

		$_SESSION['user'] = $user;
		$client = get_client($user);
		$all = true;

		$count = 0;

		$is_new_activity = false;
		$is_new_docs = false;

		$res=googleapps_sync_sites(true, $user);

		$response_list = $res['response_list']; //sites xml list
		$site_entities =$res['site_entities']; // sites objects
		$all_site_entities_count =count($res['all_site_entities']); // sites objects
		$all_site_entities =$res['all_site_entities'];

		echo "========== Processing user ".$user->name." ==========";

		$max_time = null;
		$times = array();

		$site_list = empty($user->site_list) ? array() : unserialize($user->site_list);

		if (empty($user->last_site_activity)) {
			$user->last_site_activity = '0';
		}

		// Parse server response for google sites activity stream
		foreach ($response_list as $site) {

			// found current site entity obj
			$site_entity=null;
			foreach ($site_entities as $site_obj) {
				if ($site_obj->site_id == $site['site_id']) {
					$site_entity =  $site_obj;
					break; // found
				}
			}


			$last_time_site_updated = $site_entity->modified;

			$title = $site['title'];
			$feed = $site['feed'];
			$site_exist = null;

			// update access settings for site in user site list
			save_site_to_user_list($site_entity, $site, $site_list);

			// Get google sites activity stream
			$activity_xml = $client->execute($feed, '1.1');
			$rss = simplexml_load_string($activity_xml);
			$times[] = strtotime($rss->updated);

			$site_title = $title;
			$title = 'Changes on ' . $title . ' site';


			// Parse entries for each google site
			echo "site entity id=".$site_entity->guid." site title= ".$site_entity->title." ( ".$site_entity->site_id." )\n";
			if ($site_entity->site_access_id == ACCESS_PRIVATE) { echo "site access is private\n"; }

			foreach ($rss->entry as $item) {
				// Get entry data
				$text = $item->summary->div->asXML();
				$author_email = @$item->author->email[0];
				$date = $item->updated;

				if (strtotime($date)>$last_time_site_updated) $last_time_site_updated=strtotime($date); // update site time

				$time = strtotime($date);
				$site_access = $site_entity->site_access_id;
				$access = calc_access($site_access);
				$times[] = $time; // all user's sites time

				// if sit is public
				if ($site_entity->site_access_id != ACCESS_PRIVATE) {
					if ( $user->last_site_activity <= $time // not publish already
					&& $author_email == $user->email // edited by this user
					/* &&  $site['isPublic'] == true */ )
					{
						// Initialise a new ElggObject (entity)
						$site_activity = new ElggObject();
						$site_activity->subtype = "site_activity";
						$site_activity->owner_guid = $user->guid;
						$site_activity->container_guid = $user->guid;

						$site_activity->access_id = $access;
						$site_activity->title = $title;

						$site_activity->updated = $date;

						$site_activity->text = str_replace('<a href', '<a target="_blank" href', $text) . ' on the <a target="_blank" href="' . $site['url'] . '">' . $site_title . '</a> site';
						$site_activity->site_name = $site_title;

						// Now save the object
						if (!$site_activity->save()) {
							register_error('Site activity has not saved.');
							//forward(REFERER);
						}

						if (add_to_river('river/object/site_activity/create', 'create', $user->guid, $site_activity->guid, "", strtotime($date))) {
							$is_new_activity = true;
							echo "\nPublished activity for this site</b>. Site is access is ".$access."\n";
						} else {
							echo "error adding activity to river\n";
						}

					} // need to add activite
				} // public activity

			} // rss in site

			// change site time
			if( $last_time_site_updated > $site_entity->modified ) {
				$site_entity->modified = $last_time_site_updated;
				$site_entity->save();
				echo "\nupdated last updated time.\n";
			};
		} // all sites

		if($response_list) {
			$max_time = max($times);
			$user->last_site_activity = $max_time;
			$user->save();
		}

		if (!empty($site_list)) {
			$user->site_list = serialize($site_list);
			$user->save();
		}

		if ($is_new_activity) {
			echo 'New activity added for ' . $user->username . '\n';
		} else {
			echo 'No new activity for ' . $user->username . '\n';
		}

		echo "\n\n";
	} // each user


	echo "\n\nAll finished\n";
	$b_time=time();
	echo "\n".($b_time-$a_time)." sec";
	flush();

	elgg_set_context($context);
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
 * @param object $client
 * @param bool $ajax
 * @param string $scope
 * @return object|false
 */
// googleapps_fetch_oauth_data($client, false, 'mail sites folders docs')
function googleapps_fetch_oauth_data($client, $ajax = false, $scope = null) {

	if (!is_object($client)) {
		return false;
	}

	$all = true;

	if (!empty($scope)) {
		$scope = explode(' ', $scope);
		$all = false;
	}

	$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
	$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
	$oauth_sync_docs = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');

	$count = 0;
	$is_new_docs = false;
	$user = $_SESSION['user'];
	if ($oauth_sync_email != 'no' &&
	((!$all && in_array('mail', $scope)) || $all)) {
		// Get count unread messages of gmail
		$count = $client->unread_messages();
		$_SESSION['google_mail_count'] = $count;
	}

	if ($oauth_sync_docs != 'no') {

		if ((!$all && in_array('folders', $scope)) || $all) {
			// Get google docs folders
			$folders = googleapps_google_docs_folders($client);
			$oauth_docs_folders = serialize($folders);
			$_SESSION['oauth_google_folders'] = $oauth_docs_folders;
		}

		if ((!$all && in_array('docs', $scope)) || $all) {
			// Get google docs
			$google_folder = !empty($_SESSION['oauth_google_folder']) ? $_SESSION['oauth_google_folder'] : null;
			$documents = googleapps_google_docs($client, $google_folder);
			$oauth_docs = serialize($documents);
			if (empty($_SESSION['oauth_google_docs']) || $_SESSION['oauth_google_docs'] != $oauth_docs) {
				$_SESSION['oauth_google_docs'] = $oauth_docs;
				$is_new_docs = true;
			}
		}
	}

	if ($ajax) {
		$response = array();
		$response['mail_count'] = !empty($count) ? $count : 0;
		//$response['new_activity'] = !empty($is_new_activity) ? 1 : 0;
		$response['new_docs'] = !empty($is_new_docs) ? 1 : 0;
		return json_encode($response);
	}
}

/**
 * Get google sites data and save it for user
 *
 * @param bool $do_not_redirect
 * @param object $user
 * @return array|false
 */
function googleapps_sync_sites($do_not_redirect = true, $user = null) {
	// 0. Check settings
	if (elgg_get_plugin_setting('oauth_sync_sites', 'googleapps') == 'no') {
		return false;
	}

	if($user == null) {
		$client = authorized_client($do_not_redirect);
	} else {
		$client = get_client($user);
	}

	if (!$client) {
		return false;
	}

	// 1. Get google site feeds list
	$result = $client->execute('https://sites.google.com/feeds/site/' . $client->key . '/', '1.1');
	$response_list = $client->fetch_sites($result); // Site list

	$all_site_entities = elgg_get_entities(array('type'=>'object', 'subtype'=>'site', 'limit'=>9999)); // Get all site entities


	// 2. Get local site list
	if($user == null) {
		$user =& $_SESSION['user'];
	}

	// 3. Save
	//$user_site_list = empty($user->site_list) ? array() : unserialize($user->site_list);

	// user's site list
	$merged = array();

	//	// 4.1 Update normalized sites: destroy deleted sites
	//	if($normalized_sites) {
	//		foreach ($normalized_sites as $site_entity) {
	//
	//			$found = false; // User's google site list
	//			foreach ($response_list as $site) {
	//
	//				if (empty($site_entity->site_id)) {
	//					continue;
	//				}
	//				if ($site['site_id'] == $site_entity->site_id) {
	//					$found = $site;
	//					break;
	//				}
	//			}
	//
	//			if (!$found) {
	//				/* $site_entity->delete(); */
	//			} else {
	//				$modified = false;
	//				if ($site['url'] != $site_entity->url) {
	//					$site_entity->url = $found['url'];
	//					$modified = true;
	//				}
	//				if ($site['title'] != $site_entity->title) {
	//					$site_entity->title = $found['title'];
	//					$modified = true;
	//				}
	//				if ($site['modified'] > $site_entity->modified) {
	//					$site_entity->modified = $found['modified'];
	//					$modified = true;
	//				}
	//				if ($modified) {
	//					$site_entity->save();
	//                                                echo "<h3>global site ". $site_entity->title." updated</h3>";
	//				}
	//			}
	//
	//		}
	//	}

	// site entities what have user
	$users_site_entities=array();

	// 4.2 Update normalized sites: create new sites
	foreach ($response_list as $site) {
		$found = false;

		// search for site in elgg entities
		foreach ($all_site_entities as $site_entity) {
			if ($site['site_id'] == $site_entity->site_id) {
				$users_site_entities[]=$site_entity;
				save_site_to_user_list($site_entity, $site, $merged);
				$found = true;
				break;
			}
		}

		// create new site entity
		if (!$found) {
			echo "<b> CREATED SITE ENTITY </b><br />";
			$new_site = new ElggObject();
			$new_site->owner_guid = $user->guid;
			$new_site->site_id = $site['site_id'];
			$new_site->title = $site['title'];
			$new_site->subtype = "site";
			$new_site->url = $site['url'];
			$new_site->modified = $site['modified'];
			$new_site->access_id = ACCESS_LOGGED_IN; // for entity. just for search availably
			$new_site->site_access_id = ACCESS_PRIVATE ; // for site
			$new_site->save();
			$users_site_entities[]=$new_site;
			save_site_to_user_list($new_site, $site, $merged);
		}
	}

	// 4. Update user
	$user->site_list = serialize($merged);
	$user->save();

	// 5. Profit
	return array('response_list'=>$response_list,  'site_entities'=>$users_site_entities, 'all_site_entities' => $all_site_entities );
}

/**
 * Get google docs folders for authorised client
 *
 * @param object $client
 * @return object
 */
function googleapps_google_docs_folders($client) {

	$feed = 'http://docs.google.com/feeds/default/private/full/-/folder';
	$result = $client->execute($feed, '3.0');
	$folders_rss = simplexml_load_string($result);

	$folders = folders_from_rss($folders_rss);

	return $folders;

}

/**
 * Get google docs for authorised client and folder
 *
 * @param object $client
 * @param string $folder
 * @return object
 */
function googleapps_google_docs_get_collaborators($client, $doc_id) {
	$feed = 'http://docs.google.com/feeds/acl/private/full/' . $doc_id ;

	$result = $client->execute($feed, '2.0');
	$rss = simplexml_load_string($result);

	$shared_with_users=array();

	// Parse for each permission entity
	foreach ($rss->entry as $item) {
		$user = str_replace('Document Permission - ', '', $item->title);
		$shared_with_users[]=$user;
	}

	if  (in_array('default', $shared_with_users)) {
		return 'public'; // Public document
	}

	if  (in_array('everyone', $shared_with_users)) {
		return 'everyone_in_domain'; // Shared with domain
	}

	return $shared_with_users;
}


/**
 * Change the google docs permissions based on chosen elgg permissions
 *
 * @param OAuthClient 	$client 	OAUTH client
 * @param string 		$doc_id 	Document id
 * @param mixed 		$access		Either an access_id, or an array of user's emails
 */
function googleapps_change_doc_sharing($client, $doc_id, $access) {
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

		$feed = 'http://docs.google.com/feeds/default/private/full/'. $doc_id.'/acl';

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

		$feed = 'http://docs.google.com/feeds/default/private/full/'. $doc_id.'/acl/batch';

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
 * Get google docs for authorised client and folder
 *
 * @param object $client
 * @param string $folder
 * @return object
 */
function googleapps_google_docs($client, $folder = null) {
	// Get google docs feeds list
	if (empty($folder)) {
		$feed = 'http://docs.google.com/feeds/default/private/full/-/mine';
	} else {
		$feed = 'http://docs.google.com/feeds/default/private/full/' . $folder . '/contents';
	}

	$result = $client->execute($feed, '3.0');
	$rss = simplexml_load_string($result);
	$documents = array();

	// Parse entries for each google document
	foreach ($rss->entry as $item) {

		if (count($documents) >= $max_entry) {
			//break;
		}

		$id = preg_replace('/http\:\/\/docs\.google\.com\/feeds\/id\/(.*)/', '$1', $item->id);
		$title = $item['title'];

		$collaborators = googleapps_google_docs_get_collaborators($client, $id); // get collaborators for this document
		

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
				;
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
			$doc['id']=$id;
			$doc['title'] = preg_replace('/\<title\>(.*)\<\/title\>/', '$1', $item->title->asXML());
			$doc['trunc_title'] = trunc_name($doc['title']);
			$doc['href'] = preg_replace('/href=\"(.*)\"/', '$1', $src->asXML());
			$doc['type'] = $type;
			$doc['updated'] = strtotime($item->updated);
			$doc['collaborators'] = $collaborators;
			$documents[] = $doc;
		}
	}
	return $documents;

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

function save_site_to_user_list($site_entity, $site_xml, &$merged) {
	$title = $site_xml['title'];
	$site_id = $site_xml['site_id'];
	$access = $site_entity->site_access_id;
	$merged[$site_id] = array('title'=>$title, 'access'=>$access, 'entity_id' =>  $site_entity->guid);
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
