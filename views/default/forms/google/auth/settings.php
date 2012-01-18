<?php
/**
 * Googleapps auth/sync user settings form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$user = elgg_extract('user', $vars);

$user_sync_settings = unserialize($user->sync_settings);
$enabled = array();

// If the sync name settings doesn't exist, set it here (default off)
if(!is_array($user_sync_settings)) {
	$user_sync_settings['sync_name'] = 0;
	$user->sync_settings = serialize($user_sync_settings);
	$user->save();
}

foreach ($user_sync_settings as $setting => $v) {
	if ($v) {
		$enabled[] = $setting;
	}
}

$sync_name_input = elgg_view('input/checkboxes', array(
	'name' => "sync_settings", 
	'value' => $enabled,  
	'options' => array('Syncing name upon login' => 'sync_name')
));

$submit_input = elgg_view('input/submit', array(
	'value' => elgg_echo('save'), 
	'class' => 'elgg-button elgg-button-submit'
));

$form_body = <<<HTML
	<div>
		$sync_name_input
	</div>
	<div>
		$submit_input
	</div>
HTML;

echo $form_body;