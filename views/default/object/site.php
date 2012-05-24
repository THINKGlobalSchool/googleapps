<?php
/**
 * Googleapps wiki/site object view 
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$site = elgg_extract('entity', $vars, FALSE);

if (!$site) {
	return TRUE;
}

$owner = $site->getOwnerEntity();
$container = $site->getContainerEntity();

$owners = $site->remote_owners;

if (!$owners) {
	$owners_string = "Unknown";
} else if (is_array($owners)) {
	foreach ($owners as $owner) {
		$owner_entity = get_user_by_email($owner);
		if (is_array($owner_entity) && count($owner_entity) && elgg_instanceof($owner_entity[0], 'user')) {
			$owner_link = '<a href="' . $owner_entity[0]->getURL() . '">' . $owner_entity[0]->name . '</a>';
		} else {
			$owner_link = $owner;
		}

		$owners_string .= $owner_link;

		if ($c + 1 < count($owners)) {
			$owners_string .= ', ';
		}
 		$c++;
	}
} else {
	$owner_entity = get_user_by_email($owners);
	if (is_array($owner_entity) && count($owner_entity) && elgg_instanceof($owner_entity[0], 'user')) {
		$owners_string = '<a href="' . $owner_entity[0]->getURL() . '">' . $owner_entity[0]->name . '</a>';
	} else {
		$owners_string = $owners;
	}
}

$date = elgg_view_friendly_time($site->modified);


if (elgg_in_context('sites_debug')) {
	// Admin debug view
	$date = date(DATE_ATOM, $site->modified);
	
	$access = elgg_view('output/access', array('entity' => $site));
	
	$content = <<<HTML
		<table class='googleapps-sites-debug'>
			<tbody>
				<tr>
					<td style='padding-right: 10px;'><strong>GUID:</strong></td>
					<td>$site->guid</td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>Owners:</strong></td>
					<td>$owners_string</td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>URL:</strong></td>
					<td><a href='$site->url'>{$site->url}</a></td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>Access Level:</strong> </td>
					<td>$access</td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>Last modified:</strong></td>
					<td>$date</td>
				</tr>
			</tbody>
		</table>
		<br />
HTML;
	echo elgg_view_module('info', $site->title, $content);
} else {

	if (!elgg_in_context('widgets')) {
		$site_icon = "<img src='" . elgg_get_site_url() . 'mod/googleapps/graphics/icon_site.jpg' . "' />";
		$metadata = elgg_view_menu('entity', array(
			'entity' => $site,
			'handler' => 'wikis',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
	} else {

	}

	$subtitle = "<p><strong>" . elgg_echo('googleapps:label:updated') . ":</strong> $date <br /> 
					<strong>" . elgg_echo('googleapps:label:owners') . ":</strong> $owners_string</p>";
	
	// brief view
	$params = array(
		'entity' => $site,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => 'false',
	);
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($site_icon, $list_body);
}
