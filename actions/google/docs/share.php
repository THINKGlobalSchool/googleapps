<?php
/**
 * Googleapps share doc action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$document_id = get_input('document_id', null);
$document_description = get_input('description', '');
$document_tags = get_input('tags', '');
$access_level = get_input('access_id', null);
$document_url = get_input('document_url', null);
$document_container_guid = get_input('container_guid');

// Make sure user can write to the container (group)
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $document_container_guid)) {
	register_error(elgg_echo('googleapps:error:nopermission'));
	forward(REFERER);
}

// Check for a document url/id
if (empty($document_id) && empty($document_url)) {
	register_error(elgg_echo("googleapps:error:document_id_required"));
	forward(REFERER);
}

// Grab document from API
$client = authorized_client(TRUE);
$document = googleapps_get_doc_from_id($client, $document_id);

$document_info = array();
$document_info['doc_id'] = $document_id;
$document_info['description'] = $document_description;
$document_info['access'] = $access_level;
$document_info['tags'] = $document_tags;

$collaborators = $document['collaborators'];

// If the document is public, go ahead and share it
if ($collaborators == 'public') {
	// Share document and output success
	share_document($document, $document_description, $document_tags, $access_level, $document_container_guid); // Share and public document activity
	echo elgg_view('googleapps/success', array('container_guid' => $document_container_guid));
	forward(REFERER);
} else {
	//Not public, need to warn/update permissions
	$form_vars = array(
		'container_guid' => $document_container_guid,
		'document_info' => $document_info
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

	echo elgg_view_form('google/docs/permissions', $vars, $form_vars);
	forward(REFERER);
}