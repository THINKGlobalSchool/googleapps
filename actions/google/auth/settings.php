<?php
/**
 * Google user sync settings save action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$user = elgg_get_logged_in_user_entity();

$sync_settings = get_input('sync_settings');
$user_sync_settings = unserialize($user->sync_settings);

if (!is_array($user_sync_settings)) {
	$user_sync_settings = array();
}

foreach($sync_settings as $setting) {
	$user_sync_settings[$setting] = 1;
}

foreach($user_sync_settings as $user_setting => $v) {
	if (!in_array($user_setting, $sync_settings)) {
		$user_sync_settings[$user_setting] = 0;
	}
}

$user->sync_settings = serialize($user_sync_settings);
$user->save();
system_message(elgg_echo('admin:configuration:success'));
forward(REFERER);
