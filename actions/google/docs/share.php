<?php
/**
 * Googleapps share doc action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$document_id = get_input('document_id', null);
$document_description = get_input('description', '');
$document_tags = string_to_tag_array(get_input('tags', ''));
$access_level = get_input('access_id', null);
$document_url = get_input('document_url', null);
$document_match = get_input('match_permissions', null);
$document_container_guid = get_input('container_guid');

// Make sure user can write to the container (group)
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $document_container_guid)) {
	register_error(elgg_echo('googleapps:error:nopermission'));
	forward();
}

if ($document_match && $access_level === null) {
	$access_level = GOOGLEAPPS_ACCESS_MATCH;
}

// Check for a document url/id
if (empty($document_id) && empty($document_url)) {
	register_error(elgg_echo("googleapps:error:document_id_required"));
	forward();
}

// Grab document from API
$client = authorized_client(TRUE);
$document = googleapps_get_doc_from_id($client, $document_id);

// If we have a URL find it in the list
/*
if ($document_url && !empty($document_url)) {
	// Sanitize our input url, trims out trailing '#'s as well
	$document_url = googleapps_santize_google_doc_input($document_url);

	foreach ($google_docs as $doc) {
		// Clean up doc url
		$google_url = str_replace(array('http://','https://'), '', trim(strtolower($doc['href'])));
		if ($document_url == $google_url) {
			$document = $doc;
			$document_id = $doc['id'];
			$found = true;
			break;
		}
	}
	if (!$found) {
		register_error(elgg_echo('googleapps:error:invalid_url'));
		forward();
	}
} else {
	// Browsed to document, so match id
	foreach ($google_docs as $doc) {
		if ($doc['id'] == $document_id) {
			$document = $doc;
			break;
		}
	}
}
*/

$document_info = array();
$document_info['doc_id'] = $document_id;
$document_info['description'] = $document_description;
$document_info['access'] = $access_level;
$document_info['tags'] = $document_tags;

$collaborators = $document['collaborators'];

if (!check_document_permission($collaborators, $access_level)) {
	// Output permissions form	
	$vars = array(
		'id' => 'google-docs-update-permissions',
		'name' => 'google_docs_update_permissions',
	);

	echo elgg_view_form('google/docs/permissions', $vars, array(
		'container_guid' => $document_container_guid,
		'document_info' => $document_info,
	));
	forward();
} else {
	// Share document and output success
	share_document($document, $document_description, $document_tags, $access_level, $document_container_guid); // Share and public document activity
	echo elgg_view('googleapps/success', array('container_guid' => $document_container_guid));
	forward();
}
