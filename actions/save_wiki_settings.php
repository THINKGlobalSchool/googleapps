<?php
/**
 * Googleapps wiki settings save action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

gatekeeper();

global $SESSION;

$googleapps_controlled_profile = strip_tags(get_input('googleapps_controlled_profile'));
$googleapps_sites_settings = $_POST['googleapps_sites_settings'];

$user_id = get_input('guid');
$user = "";
$error = false;
$synchronize = false;

if (!$user_id) {
	$user = $_SESSION['user'];
} else {
	$user = get_entity($user_id);
}

$subtype = $user->getSubtype();

if ($user->google == 1) {
	if ($googleapps_controlled_profile == 'no' && empty($user->password)) {
		register_error(sprintf(elgg_echo('googleapps:googleappserror'), elgg_echo('googleapps:passwordrequired')));
		forward($_SERVER['HTTP_REFERER']);
	}
	if (elgg_strlen($googleapps_controlled_profile) > 50) {
		register_error(elgg_echo('admin:configuration:fail'));
		forward($_SERVER['HTTP_REFERER']);
	}
	if (($user) && ($user->canEdit())) {
		if ($googleapps_controlled_profile != $user->googleapps_controlled_profile) {
			if (!$user->save()) {
				$error = true;
			}
		}	
		if (!empty($googleapps_sites_settings)) {
			$site_list = unserialize($user->site_list);
			foreach ($googleapps_sites_settings as $site_id => $access) {
				$site_list[$site_id]['access'] = $access;
        		$entity_id = $site_list[$site_id]['entity_id'];
        		update_site_entity_access($entity_id, $access);
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

forward($_SERVER['HTTP_REFERER']);