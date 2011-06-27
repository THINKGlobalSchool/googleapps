<?php
/**
 * Elgg googleapps group documents list
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$group = elgg_get_page_owner_entity();

if ($group->shared_doc_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "googleapps/docs/group/$group->guid/owner",
	'text' => elgg_echo('link:view:all'),
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'shared_doc',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('googleapps:docs:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "googleapps/docs/add/$group->guid",
	'text' => elgg_echo('googleapps:label:shareadoc'),
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('googleapps:label:google_docs'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
