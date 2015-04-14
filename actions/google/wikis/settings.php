<?php
/**
 * Googleapps wiki settings save action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$sites_access = get_input('sites_access');

if (!empty($sites_access)) {
	$guids = array_keys($sites_access);

	$sites = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'site', 
		'guids' => $guids,
		'limit' => 0,
	));

	foreach ($sites as $site) {
		$site->access_id = $sites_access[$site->guid];
		$site->save();
	}	
	
	system_message(elgg_echo('admin:configuration:success'));
} else {
	register_error(elgg_echo('admin:configuration:fail'));	
}

forward(REFERER);