<?php
/**
 * Googleapps save user sync action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

gatekeeper();

global $SESSION;
$user = $_SESSION['user'];

$sync_settings = get_input('sync_settings');
$user_sync_settings = unserialize($user->sync_settings);

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
forward($_SERVER['HTTP_REFERER']);