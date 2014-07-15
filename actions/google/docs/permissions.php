<?php
/**
 * Update/Change google doc permissions action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$description = get_input('description');
$tags = get_input('tags');
$access_id = get_input('access');
$document_id = get_input('doc_id');
$container_guid = get_input('container_guid');
$action = get_input('permissions_action');
$entity_guid = get_input('entity_guid', FALSE);
$success_class = get_input('success_class');

// Make sure user can write to the container (group)
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $container_guid)) {
	register_error(elgg_echo('googleapps:error:nopermission'));
	forward();
}

// Get google doc
$client = googleapps_get_client();
$client->setAccessToken(googleapps_get_user_access_tokens());
$document = googleapps_get_file_from_id($client, $document_id);

// Update permissions if action is either public or domain
if ($action == 'public' || $action == 'domain') {
	googleapps_update_file_permissions($client, $document->getId(), $action);
}

// Share document
googleapps_save_shared_document($document, array(
	'description' => $description,
	'access_id' => $access_id,
	'tags' => $tags,
	'container_guid' =>  $container_guid,
	'entity_guid' => $entity_guid
));

echo elgg_view('googleapps/success', array(
	'container_guid' => $container_guid,
	'success_class' => $success_class
));

forward(REFERER);
