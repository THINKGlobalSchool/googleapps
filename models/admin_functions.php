<?php
/**
 * Functions for use by admin pages
 *
 * @package GoogleAppsLogin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mike Hourahine
 * @copyright THINK Global School 2010
 * @link http://thinkglobalschool.org/
 */

function list_googlesite_entities() {
	$output = "";

	$site_entities = elgg_get_entities(array('type'=>'object', 'subtype'=>'site', 'limit'=>999));
	$site_count = count($site_entities);
	
	$output .= "<p>Site entities found: {$site_count}</p>";
	foreach($site_entities as $site_entity) {
		$output .= elgg_view('googleappslogin/admin/site_entity',array('site_entity'=>$site_entity));
		$output .= "<br/>";
	}
	
	return $output;
}

function list_googlesite_entities_byuser() {
	$output = "";
	
	$googleusers =find_metadata('googleapps_controlled_profile', 'yes', 'user', '', 999);
	foreach($googleusers as $googleuser) {
		$user = get_user($googleuser->owner_guid);
		$site_list = empty($user->site_list) ? array() : unserialize($user->site_list);
		
		$output .= "<p>Sites found for <strong>{$user->name}</strong>:";
		foreach($site_list as $key => $site) {
			$site_entity = get_entity($site['entity_id']);
			$output .= elgg_view('googleappslogin/admin/site_entity',array('site_entity'=>$site_entity));
		}
		$output .= "<hr />";	 
	}
	return $output;
}
