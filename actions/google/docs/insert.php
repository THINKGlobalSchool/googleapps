<?php
/**
 * Google docs insert action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$document_link = get_input('doc_link');
$document_id = get_input('doc_id');

// Get google doc
$client = authorized_client(TRUE);
$document = googleapps_get_doc_from_id($client, $document_id);

$document_info = array();
$document_info['doc_id'] = $document_id;

$collaborators = $document['collaborators'];

// echo json_encode(array(
// 	'id' => $document_id,
// 	'link' => $document_link
// ));

// If the document is public, go ahead and insert the link
if ($collaborators == 'public') {
	echo json_encode(array('insert_status' => 1, 'form' => $form));
	forward(REFERER);
} else {
	//Not public, need to warn/update permissions
	$form_vars = array(
		'document_info' => $document_info,
		'do_create' => 'no',
		'success_class' => 'googleapps-docs-insert-success'
	);

	// If this document is shared to the domain, warn and give the option to share publicly
	if ($collaborators == 'domain') {
		$form_vars['access'] = $collaborators;
		$form_vars['options'] = array('public', 'ignore');
	} else {
		// Unshared or shared to specific folks, warn and allow sharing with domain/public
		$form_vars['access'] = 'other';
		$form_vars['options'] = array('domain', 'public', 'ignore');
	}

	// Output permissions form	
	$vars = array(
		'id' => 'google-docs-update-permissions',
		'name' => 'google_docs_update_permissions',
	);

	$form = elgg_view_form('google/docs/permissions', $vars, $form_vars);
	echo json_encode(array('insert_status' => 'need_update', 'form' => $form));

	forward(REFERER);
}