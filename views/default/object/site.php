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

$site_icon = "<img src='" . elgg_get_site_url() . 'mod/googleapps/graphics/icon_site.jpg' . "' />";

$owner = get_entity($site->owner_guid);
$owners = array();
$owners[] = $owner;

// This never worked.. 'other_owners' isn't set anywhere. WTF is up with that?
// @TODO make it work?
$other_owners = array();
if (!empty($site->other_owners)) {
	$other_owners = unserialize($site->other_owners);
	foreach ($other_owners as $owner) {
		$owners[] = get_entity($owner);
	}
}
$c = 0;
$owners_string = '';
foreach ($owners as $owner) {
	$owners_string .= '<a href="' . $owner->getURL() . '">' . $owner->name . '</a>';
	if ($c + 1 < count($owners)) {
		$owners_string .= ', ';
	}
	$c++;
}

$date = elgg_view_friendly_time($site->modified);

$subtitle = "<p><strong>" . elgg_echo('googleapps:label:updated') . ":</strong> $date <br /> 
				<strong>" . elgg_echo('googleapps:label:owners') . ":</strong> $owners_string</p>";

if ($vars['debug'] == TRUE) {
	// Admin debug view
	
	$date = date(DATE_ATOM, $site->modified);
	
	$content = <<<HTML
		<hr />
		<table class='googleapps-sites-debug'>
			<tbody>
				<tr>
					<td style='padding-right: 10px;'><strong>GUID:</strong> </td>
					<td>$owner->username ($owner->guid)</td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>Owner:</strong></td>
					<td>$site->guid</td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>URL:</strong></td>
					<td><a href='$site->url'>{$site->url}</a></td>
				</tr>
				<tr>
					<td style='padding-right: 10px;'><strong>Access Level:</strong> </td>
					<td>$site->site_access_id</td>
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
