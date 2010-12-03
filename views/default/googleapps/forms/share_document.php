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
	
// Check if we've got an entity, if so, we're editing.
if (isset($vars['entity'])) {
	
	if (!$vars['entity']) {
		forward();
	}
	
	$action 			= ''; // do something here
	$title 		 		= $vars['entity']->title;
	$description 		= $vars['entity']->description;
	$tags 				= $vars['entity']->tags;
	$access_id			= $vars['entity']->access_id;
	$entity_hidden  = elgg_view('input/hidden', array('internalname' => 'document_guid', 'value' => $vars['entity']->getGUID()));

	
	
} else {
// No entity, creating new one
	$action 			= $GLOBALS['share_doc_url']; // share new document
	$description 		= "";
	$entity_hidden = "";
}

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());
$container_hidden = elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $container_guid));

// Labels/Input
$description_label = elgg_echo("description");
$description_input = elgg_view("input/longtext", array('internalname' => 'description', 'value' => $description));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('internalname' => 'tags', 'value' => $tags));

// Going to hack in the groups to this access list
$context = get_context();
set_context('googleapps_share_doc');
$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('internalname' => 'access_id', 'internalid' => 'access_id', 'value' => ACCESS_LOGGED_IN));
set_context($context);

$match_label = elgg_echo('googleapps:label:match_permissions');
$match_input = elgg_view('input/pulldown', array(	'internalname' => 'match_permissions',
													'internalid' => 'match_permissions',
													'options_values' =>	array(	0 => 'No',
																				1 => 'Yes')		
													));
													
$match_tooltip = "<a class='gapps_tooltip' href='#'>" . elgg_echo('googleapps:label:tooltipname') . "<span>" . elgg_echo('googleapps:tooltip:match') . "</span></a>";

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));	

// Browse document container
$browse_content .= '<div id="googleapps">
				<div id="google_docs_loading">
					<img src="' . elgg_get_site_url() . '_graphics/ajax_loader_bw.gif" />
					<p>' . elgg_echo('googleapps:label:loading') . '</p>
				</div>
			</div>';

// For ajax
$form_url = elgg_get_site_url() . 'pg/googleapps/docs/list_form';

// Build Form Body
$form_body = <<<EOT
<form action="$action" method="post" onsubmit="return ajax_submit(this)" >
	<div class='margin_top'>
		$browse_content
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
			<label id='access_label'>$access_label</label>
			$access_input
		</div><br />
		<div>
			$submit_input
			$container_hidden
			$entity_hidden
		</div>
	</div>
</form>
EOT;

$script = <<<EOT
	<script type="text/javascript">
		
	$('#match_permissions').change(function() {
		if ($(this).val() == 0) {
			$('#access_id').removeAttr('disabled');
			$('#access_label').removeAttr('style');
		} else {
			$('#access_id').attr('disabled', 'disabled');
			$('#access_label').attr('style', 'color: #999999');
		}
	});
	
	function load_docs() {
		$("#googleapps").load("$form_url");
	}

	function ajax_submit(x) {
		var data = {};
		$($(x).serializeArray()).each(function (i, e) {
			data[e.name] = e.value;
			// TinyMCE does some voodoo magic.. need to account for that
			if (e.name == 'description' && tinyMCE) {
				data[e.name] = tinyMCE.get('description').getContent();
			}
		});
		$.post(x.action.replace(/^http(s?):\/\/.*?\//, "/"), data, function (r) {
			var dlg = $("<div id='googleappsdialog'></div>").html(r).dialog({
								width: 500, 
								modal: true,
								open: function(event, ui) { 
									$(".ui-dialog-titlebar-close").hide(); 	
								},
								buttons: {
									"X": function() { 
										$(this).dialog("close"); 
									} 
								}}).find('form').submit(function () {
				dlg.parents('.ui-dialog').remove();
			});
			if (r.toUpperCase() === 'OK') {
				load_docs();
			}
		});
		return false;
	}
	$(load_docs);
	</script>

EOT;
//$groups = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => get_loggedin_userid(), 'inverse_relationship' => FALSE, 'limit' => 999));
//print_r_html($groups);

echo $form_body . $script;
?>