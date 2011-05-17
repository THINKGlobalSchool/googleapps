<?php
/**
 * Googleapps wiki settings save action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */


$googleapps_sites_settings = get_input('googleapps_sites_settings');

$error = false;

$user = elgg_get_logged_in_user_entity();

if (!$user) {
	forward();
}

if ($user->google == 1) {
	if (($user) && ($user->canEdit())) {
		if (!empty($googleapps_sites_settings)) {
			$site_list = unserialize($user->site_list);
			foreach ($googleapps_sites_settings as $site_id => $access) {
				$site_list[$site_id]['access'] = $access;
				$entity_id = $site_list[$site_id]['entity_id'];
				googleapps_update_site_entity_access($entity_id, $access);
			}
			$user->site_list = serialize($site_list);
			$user->save();
		}
	} else {
		$error = true;
	}

	if (!$error) {
		system_message(elgg_echo('admin:configuration:success'));
	} else {
		register_error(elgg_echo('admin:configuration:fail'));
	}
}

forward(REFERER);
