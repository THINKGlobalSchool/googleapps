<?php
/**
 * Googleapps shared document object listing
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$full = elgg_extract('full_view', $vars, FALSE);
$doc = elgg_extract('entity', $vars, FALSE);

if (!$doc) {
	return TRUE;
}

$owner = $doc->getOwnerEntity();
$container = $doc->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($doc->description);

$owner_icon = elgg_view_entity_icon($owner, 'tiny');

if ($doc->icon) {
	$icon_src = $doc->icon; 
} else {
	$icon_src = elgg_get_site_url() . 'mod/googleapps/graphics/drive_icon.png';
}

$icon = elgg_view('output/img', array(
	'src' => $icon_src,
	'class' => 'google-docs-file-icon'
));

$owner_link = elgg_view('output/url', array(
	'href' => "googleapps/docs/owner/$owner->username",
	'text' => $owner->name,
));

$author_text = elgg_echo('googleapps:label:shared_by', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $doc->tags));
$date = elgg_view_friendly_time($doc->time_created);

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'google/docs',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "<p>$author_text $date</p>";
$subtitle .= $categories;

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

// brief view
$params = array(
	'entity' => $doc,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'tags' => $tags,
	'content' => $excerpt,
);
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body);
