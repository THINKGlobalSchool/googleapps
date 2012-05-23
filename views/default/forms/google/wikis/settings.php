<?php
/**
 * Googleapps wikis/sites settings form
 * 
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$user = elgg_extract('user', $vars);

if ($user->connect == 1) {
	$user->google = 1;
}

//$response = googleapps_sync_sites();
$user_site_entities = $response['site_entities'];

$_SESSION['user_site_entities'] = serialize($user_site_entities);

$site_list = unserialize($user->site_list);		
	
if (!empty($site_list)) {
	$description = elgg_echo('googleapps:usersettings:sites_description');

	$table = '<table class="elgg-table">
		<thead>
			<tr>
				<th width="75%">' . elgg_echo('googleapps:label:site') . '</th>
				<th width="25%">' . elgg_echo('googleapps:label:access_level') . '</th>
			</tr>
		</thead>
		<tbody>';
	
	
	foreach ($site_list as $site_id => $site_obj) {

		$title = $site_obj['title'];
		$access = $site_obj['access'];

		if (!empty($title)) {
			if (is_null($access)) {
				$access = 1;
			}

			$access_input = elgg_view('input/access', array(
					'name' => 'googleapps_sites_settings[' . $site_id . ']',
					'value' => $access
			));
			
			$table .= '<tr>
						<td>' . $title . '</td>
						<td>' . $access_input . '</td>
					</tr>';

		}
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
}
