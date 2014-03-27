<?php
/**
 * Googleapps document share form
 * 
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

elgg_load_js('elgg.googlefilepicker');
elgg_load_js('google-js-api');
elgg_load_js('google-doc-picker-client');
elgg_load_css('googleapps-jquery-ui');

// Check if we've got an entity, if so, we're editing.
$entity = get_entity(elgg_extract('guid', $vars, false));
if ($entity) {
	if (!elgg_instanceof($entity, 'object', 'shared_doc')) {
		forward(REFERER);
	}

	$description = $entity->description;
	$tags = $entity->tags;
	$access_id = $entity->access_id;
	$document_id = $entity->res_id;
	$icon = $entity->icon;

	$title = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->href,
		'target' => '_blank'
	));

	$modified = date('j M Y', $entity->updated);

	$entity_hidden = elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $entity->guid
	));

} else {
	// No entity, creating new one
	$description = "";
	$entity_hidden = "";

	// Get shared doc post vars
	$icon = get_input('icon');
	$document_id = get_input('document_id');
	$title = get_input('title');
	$modified = get_input('modified');
}

if (!$document_id) {
	$select_text = elgg_echo('googleapps:label:selectfile');
	$div_class = 'hidden';
	$link_class = 'elgg-button elgg-button-action';
} else {
	$select_text = elgg_echo('googleapps:label:change');
}

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());
$container_hidden = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

// Labels/Input
$choose_input = elgg_view('output/url', array(
	'text' => $select_text,
	'class' => "google-doc-picker google-doc-picker-change {$link_class}",
	'href' => '#'
));

$document_id_input = elgg_view('input/hidden', array(
	'name' => 'document_id',
	'value' => $document_id
));


if (!$icon) {
	$icon = elgg_get_site_url() . 'mod/googleapps/graphics/drive_icon.png';
}
$document_icon = elgg_view('output/img', array(
	'src' => $icon,
	'id' => 'google-docs-selected-icon',
	'class' => $icon_class
));

if (!$modified) {
	$modified_class = 'hidden';
}

$modified = '<span id="google-docs-selected-modified" class="' . $modified_class .'">' . $modified . '</span>';

$description_label = elgg_echo("description");
$description_input = elgg_view("input/longtext", array(
	'id' => 'description', 
	'name' => 'description', 
	'value' => $description
));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array(
	'name' => 'tags', 
	'value' => $tags
));

// Going to hack in the groups to this access list
$context = elgg_get_context();

elgg_set_context('googleapps_share_doc');

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id', 
	'id' => 'google-docs-access-id', 
	'value' => ACCESS_LOGGED_IN
));

elgg_set_context($context);

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));	

// Build Form Body
$form_body = <<<HTML
<div>
	<div id='google-docs-selected'>
		<div id='google-docs-selected-inner' class='$div_class'>
			$document_icon
			<span id="google-docs-selected-title">$title</span>
			$modified
			$document_id_input
		</div>
		$choose_input
	</div>
	<div>
		<label>$description_label</label><br />
        $description_input
	</div><br />
	<div>
		<label>$tag_label</label><br />
        $tag_input
	</div><br />
	<div>
		<label>$match_label</label>
		$match_input
		$match_tooltip
	</div><br />
	<div>
		<label id='google-docs-access-id-label'>$access_label</label>
		$access_input
	</div><br />
	<div class="elgg-foot">
		$submit_input
		$container_hidden
		$entity_hidden
	</div>
</div>
HTML;

echo $form_body . $script;
