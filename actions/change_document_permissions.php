<?php
/**
 * Update/Change google doc permissions action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$data = unserialize($_SESSION['google_docs_to_share_data']);
$description = $data['description'];
$tags = $data['tags'];
$access_id = $data['access'];
$document_id = $data['doc_id'];
$container_guid = get_input('container_guid');

// Make sure user can write to the container (group)
if (!can_write_to_container(get_loggedin_userid(), $container_guid)) {
	echo elgg_echo('googleapps:error:nopermission');
}

// Get google docs from session
$google_docs = unserialize($_SESSION['oauth_google_docs']);

// Get the selected document
foreach ($google_docs as $doc) {
	if ($doc['id'] == $document_id) {
		$document = $doc;
		break;
	}
}

$client = authorized_client(true);

switch (get_input('answer')) {
    case elgg_echo('googleapps:submit:grant'):
		if ($members = get_members_of_access_collection($access_id)) {
			// We've got a group ACL
			$members_email = get_members_emails($members);
			$share_to = get_members_not_shared($members_email, $document);
			googleapps_change_doc_sharing($client, $document['id'], $share_to) ; // change permissions
            share_document($document, $description, $tags, $access_id, $container_guid);
            break;
		}
        googleapps_change_doc_sharing($client, $document['id'], $access_id) ;
        share_document($document, $description, $tags, $access_id, $container_guid);
	break;
    case elgg_echo('googleapps:submit:ignore'):
        share_document($document, $description, $tags, $access_id, $container_guid);
    break;
}
     die ( elgg_echo("googleapps:success:shared") );
     exit;

?>
