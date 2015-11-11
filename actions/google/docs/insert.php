<?php
/**
 * Google docs insert action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$document_id = get_input('doc_id');

// Get google doc and permissions
try {
	$client = googleapps_get_client();
	$client->setAccessToken(googleapps_get_user_access_tokens());
	$permissions = googleapps_get_file_permissions_from_id($client, $document_id);
	$document = googleapps_get_file_from_id($client, $document_id);
} catch (Exception $e) {
	register_error($e->getMessage());
	forward(REFERER);
}

$document_info = array();
$document_info['doc_id'] = $document_id;

// Determine if this document is available to the public
foreach ($permissions as $permission) {
	if ($permission->getType() == 'anyone') {
		$is_public = TRUE;
	} else if ($permission->getType() == 'domain') {
		$is_domain = TRUE;
	}
}

// If the document is public, go ahead and insert the link
if ($is_public) {
	echo json_encode(array('insert_status' => 1));
	forward(REFERER);
} else {
	//Not public, need to warn/update permissions
	$form_vars = array(
		'document_info' => $document_info,
		'do_create' => 'no',
		'success_class' => 'googleapps-docs-insert-success',
		'context' => 'insert'
	);

	// If this document is shared to the domain, warn and give the option to share publicly
	if ($is_domain) {
		$form_vars['access'] = 'domain';
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