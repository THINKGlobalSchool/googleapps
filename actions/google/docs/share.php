<?php
/**
 * Googleapps share doc action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$document_id = get_input('document_id', null);
$document_description = get_input('description', '');
$document_tags = get_input('tags', '');
$access_level = get_input('access_id', null);
$document_url = get_input('document_url', null);
$document_container_guid = get_input('container_guid');
$document_entity_guid = get_input('entity_guid', FALSE);

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

// Get client
$client = googleapps_get_client();
$client->setAccessToken(googleapps_get_user_access_tokens());

// Get file and permissions from document
$permissions = googleapps_get_file_permissions_from_id($client, $document_id);
$document = googleapps_get_file_from_id($client, $document_id);

// Determine if this document is available to the public
foreach ($permissions as $permission) {
	if ($permission->getType() == 'anyone') {
		$is_public = TRUE;
	} else if ($permission->getType() == 'domain') {
		$is_domain = TRUE;
	}
}
$document_info = array();
$document_info['doc_id'] = $document_id;
$document_info['description'] = $document_description;
$document_info['access'] = $access_level;
$document_info['tags'] = $document_tags;

// If the document is public, go ahead and share it
if ($is_public) {
	// Share document and output success
	googleapps_save_shared_document($document, array(
		'description' => $document_description, 
		'access_id' => $access_level,
		'tags' => $document_tags,
		'container_guid' =>  $document_container_guid,
		'entity_guid' => $document_entity_guid
	));

	echo elgg_view('googleapps/success', array('container_guid' => $document_container_guid));
	forward(REFERER);
} else {
	//Not public, need to warn/update permissions
	$form_vars = array(
		'container_guid' => $document_container_guid,
		'entity_guid' => $document_entity_guid,
		'document_info' => $document_info
	);

	// Check ownership. Note: this will only work for connected google accounts as user email addresses
	// must match the google account address
	foreach ($document->getOwners() as $owner) {
		if ($owner->getEmailAddress() == elgg_get_logged_in_user_entity()->email) {
			$is_owner = TRUE;
		}
		break;
	}
	if ($is_owner) {
		// If this document is shared to the domain, warn and give the option to share publicly
		if ($is_domain) {
			$form_vars['access'] = 'domain';
			$form_vars['options'] = array('public', 'ignore');
		} else {
			// Unshared or shared to specific folks, warn and allow sharing with domain/public
			$form_vars['access'] = 'other';
			$form_vars['options'] = array('domain', 'public', 'ignore');
		}
	} else {
		$form_vars['access'] = 'unowned';
		$form_vars['options'] = array('ignore');
	}

	// Output permissions form	
	$vars = array(
		'id' => 'google-docs-update-permissions',
		'name' => 'google_docs_update_permissions',
	);

	echo elgg_view_form('google/docs/permissions', $vars, $form_vars);
	forward(REFERER);
}