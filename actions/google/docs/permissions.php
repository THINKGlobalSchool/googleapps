<?php
/**
 * Update/Change google doc permissions action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$description = get_input('description');
$tags = get_input('tags');
$access_id = get_input('access');
$document_id = get_input('doc_id');
$container_guid = get_input('container_guid');

// Make sure user can write to the container (group)
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $container_guid)) {
	register_error(elgg_echo('googleapps:error:nopermission'));
	forward();
}

// Get google doc
$client = authorized_client(TRUE);
$document = googleapps_get_doc_from_id($client, $document_id);

switch (get_input('answer')) {
	case elgg_echo('googleapps:submit:grant'):
		if ($members = get_members_of_access_collection($access_id)) {
			// We've got a group ACL
			$members_email = get_members_emails($members);
			$share_to = get_members_not_shared($members_email, $document);
			googleapps_update_doc_permissions($client, $document['id'], $share_to) ; // change permissions
			share_document($document, $description, $tags, $access_id, $container_guid);
			break;
		}
		googleapps_update_doc_permissions($client, $document['id'], $access_id) ;
		share_document($document, $description, $tags, $access_id, $container_guid);
		break;
	case elgg_echo('googleapps:submit:ignore'):
		share_document($document, $description, $tags, $access_id, $container_guid);
		break;
}
echo elgg_view('googleapps/success', array('container_guid' => $container_guid));
forward();
