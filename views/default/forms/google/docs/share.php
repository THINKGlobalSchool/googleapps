<?php
/**
 * Googleapps document share form
 * 
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

elgg_load_js('elgg.googledocbrowser');
elgg_load_css('googleapps-jquery-ui');

// Check if we've got an entity, if so, we're editing.
if (isset($vars['entity'])) {
	/* We don't really edit these.. I suppose we could.. 
	
	if (!$vars['entity']) {
		forward();
	}
	
	$action 			= ''; // do something here
	$title 		 		= $vars['entity']->title;
	$description 		= $vars['entity']->description;
	$tags 				= $vars['entity']->tags;
	$access_id			= $vars['entity']->access_id;
	$entity_hidden  = elgg_view('input/hidden', array('name' => 'document_guid', 'value' => $vars['entity']->getGUID()));
	*/

} else {
// No entity, creating new one
	$description = "";
	$entity_hidden = "";
}

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());
$container_hidden = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

// Labels/Input
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

$match_label = elgg_echo('googleapps:label:match_permissions');
$match_input = elgg_view('input/dropdown', array(
	'name' => 'match_permissions',
	'id' => 'google-docs-match-permissions',
	'options_values' =>	array(
							0 => 'No',
							1 => 'Yes'
						)		
));
													
$match_tooltip = "<a class='googleapps-tooltip' href='#'>" . elgg_echo('googleapps:label:tooltipname') . "<span>" . elgg_echo('googleapps:tooltip:match') . "</span></a>";

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));	

// Table labels
$select_label = elgg_echo('googleapps:label:table_select');
$name_label = elgg_echo('googleapps:label:table_name');
$collaborators_label = elgg_echo('googleapps:label:table_collaborators');
$updated_label = elgg_echo('googleapps:label:table_updated');

// Build Form Body
$form_body = <<<HTML
<div>
	<div id="googleapps-docs-container">
		<div id="google-docs-browser">
			<table id='google-docs-table' class="elgg-table" width="100%">
				<thead>
					<tr>
						<th class='google-docs-table-select'>$select_label</th>
						<th class='google-docs-table-name'>$name_label</th>
						<th class='google-docs-table-collaborators'>$collaborators_label</th>
						<th class='google-docs-table-updated'>$updated_label</th>
					</tr>
				</thead>
				<tbody>
					<tr id='google-docs-table-loader'>
						<td colspan='4'>
							<div class='elgg-ajax-loader'>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div id='google-docs-paging'></div>
		</div>
	</div><br />
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
