<?php
/**
 * Googleapps wikis/sites settings form
 * 
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

$sites = elgg_get_entities(array(
	'type'=>'object', 
	'subtype'=>'site', 
	'limit'=>0
));
	
if (!empty($sites)) {
	$description = elgg_echo('googleapps:usersettings:sites_description');

	$table = '<table class="elgg-table">
		<thead>
			<tr>
				<th width="75%">' . elgg_echo('googleapps:label:site') . '</th>
				<th width="25%">' . elgg_echo('googleapps:label:access_level') . '</th>
			</tr>
		</thead>
		<tbody>';

	foreach ($sites as $site) {
		$title = $site->title;
		$access = $site->access_id;

		$access_input = elgg_view('input/access', array(
				'name' => "sites_access[{$site->guid}]",
				'value' => $access
		));
		
		$table .= "<tr><td>{$title}</td><td>{$access_input}</td></tr>";
	}
	
	$submit_input = elgg_view('input/submit', array(
		'value' => elgg_echo('save'), 
		'class' => 'elgg-button elgg-button-submit'
	));
	
	$table .= "</tbody></table>";
	
	echo <<<HTML
		<div>$description</div>
		<div>$table</div>
		<div>$submit_input</div>
HTML;
} else {
	echo "<label>" . elgg_echo('googleapps:label:nosites') . "</label>";
}
