<?php
	/**
	 * Googleapps content helper function
	 * 
	 * @package Googleapps
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */
	
	/* Get wiki activity settings content */
	function googleapps_get_page_content_settings_wikiactivity() {
		$content_info['title'] = elgg_echo('googleapps:google_sites_settings');		
		$content_info['content'] = elgg_view_title($content_info['title']) . elgg_view('googleapps/forms/wiki_settings');
		$content_info['layout'] = 'one_column_with_sidebar';
		return $content_info;
	}
	
	/* Get debug settings content */
	function googleapps_get_page_content_settings_debug($tab) {
		$content = elgg_view('googleapps/admin/sitesdebug_nav',array('page' => $tab));

		switch ($tab) {
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
		
		$content_info['title'] = elgg_echo("googleapps:admindebugtitle");
		$content_info['content'] = elgg_view_title($content_info['title']) . $content;
		$content_info['layout'] = 'administration';
		return $content_info;
	}
	
	/* Get account settings content */ 
	function googleapps_get_page_content_settings_account() {
		$content_info['title'] = elgg_echo('googleapps:google_sync_settings');
 		$content_info['content'] = elgg_view_title($content_info['title']) . elgg_view('googleapps/forms/sync_form');
		$content_info['layout'] = 'one_column_with_sidebar';
		return $content_info;
	}
	
	/* Get google docs content */
	function googleapps_get_page_content_docs() {
		$content_info['title'] = elgg_echo('googleapps:google_docs');
		$content_info['content'] = elgg_view_title($content_info['title']) . elgg_view('googleapps/docs_container');
		$content_info['layout'] = 'one_column';
		return $content_info;
	}
	
	/* Get google sites/wiki content */
	function googleapps_get_page_content_wikis($username = null) {
		// Google sites pages
		if (!$username) {
			// Check if we were supplied a username
			$all = true;
		}
		$postfix = $all ? 'everyone' : 'your';
		if ($all) {
			// list of all sites
			$sites = elgg_get_entities(array('type' => 'object', 'subtype' => 'site'));
		} else {
			// get list of logged in users sites
	        $res = googleapps_sync_sites(true, $user);
			$sites = $res['site_entities'];
		}
		
		$content_info['title'] = elgg_echo('googleapps:sites:' . $postfix);
		$content_info['content']  = elgg_view_title($content_info['title']) . elgg_view('googleapps/wiki_list', array('wikis' => $sites));
		$content_info['layout'] = 'one_column';
		return $content_info;
		
	}
?>