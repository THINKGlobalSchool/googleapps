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

if ($document_match && $access_level === null) {
	$access_level = GOOGLEAPPS_ACCESS_MATCH;
}

// Check for a document url/id
if (empty($document_id) && empty($document_url)) {
    echo elgg_echo("googleapps:error:document_id_required");
    exit;
}

$google_docs = unserialize($_SESSION['oauth_google_docs']);

// If we have a URL find it in the list
if ($document_url && !empty($document_url)) {
	// Sanitize our input url, trims out trailing '#'s as well 
	$document_url = santize_google_doc_input($document_url);
		
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
		echo elgg_echo('googleapps:error:invalid_url');
		exit;
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

$document_info = array();
$document_info['doc_id'] = $document_id;
$document_info['description'] = $document_description;
$document_info['access'] = $access_level;
$document_info['tags'] = $document_tags;
$_SESSION['google_docs_to_share_data'] = serialize($document_info); // remember data


$collaborators = $document['collaborators'];

if (!check_document_permission($collaborators, $access_level) ) {
	echo elgg_view('googleapps/forms/docs_permissions');
	exit;
} else {
	share_document($document, $document_description, $document_tags, $access_level); // Share and public document activity
	echo elgg_echo("googleapps:success:shared");
	exit;
 }
?>
