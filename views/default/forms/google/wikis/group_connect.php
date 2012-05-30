<?php
/**
 * Googleapps Group Wiki Form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$group = elgg_extract('entity', $vars);

if (!elgg_instanceof($group, 'group')) {
	return;
}

// Wiki/site options
$options = array(
	'type' => 'object', 
	'subtype' => 'site',
	'limit' => 0,
);

$relationship = GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP;
$dbprefix = elgg_get_config('dbprefix');
$group = elgg_extract('entity', $vars);

// Where clause to ignore wikis that already have a relationship with another group
$options['wheres'] = "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}entity_relationships r2 
		WHERE r2.guid_one = e.guid
		AND r2.relationship = '{$relationship}')";

// Grab as a batch
$wikis = new ElggBatch('elgg_get_entities', $options);

$wiki_array = array();

// Create an array of guid -> title
foreach ($wikis as $wiki) {
	$wiki_array[$wiki->guid] = $wiki->title;
}

// Labels
$wiki_select_label = elgg_echo('googleapps:label:availablewikis');
$connected_label = elgg_echo('googleapps:label:connectedwikis');

// Current connected wiki's
$options = array(
	'type' => 'object',
	'subtype' => 'site',
	'limit' => 0,
	'full_view' => FALSE,
	'relationship' => GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP, 
	'relationship_guid' => $group->guid, 
	'inverse_relationship' => TRUE,
);

elgg_push_context('group_connected_wikis');
$list = elgg_list_entities_from_relationship($options);
elgg_pop_context();

// Inputs
$wiki_select = elgg_view('input/dropdown', array(
	'name' => 'wiki_guid',
	'options_values' => $wiki_array,
));

$group_input = elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

$wiki_submit = elgg_view('input/submit', array(
	'name' => 'wiki_submit',
	'value' => elgg_echo('googleapps:label:connectwiki'),
));

$form_content = <<<HTML
	<div>
		<label>$wiki_select_label</label>: 
		$wiki_select
	</div>
	<div class='elgg-foot'>
		$wiki_submit
		$group_input
	</div>
HTML;

if ($list) {
	$list_content = <<<HTML
		<br />
		<h3>$connected_label</h3>
		$list
HTML;
}

$form = elgg_view("input/form", array("body" => $form_content,
	"action" => elgg_get_site_url() . "action/google/wikis/group_connect",
	"id" => "googleapps-wiki-group-connect-form"
));

echo elgg_view_module('info', elgg_echo('googleapps:label:groupwikis'), $form . $list_content);