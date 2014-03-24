<?php
/**
 * Update/Change google doc permissions action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$description = get_input('description');
$tags = get_input('tags');
$access_id = get_input('access');
$document_id = get_input('doc_id');
$container_guid = get_input('container_guid');
$action = get_input('permissions_action');

// Make sure user can write to the container (group)
if (!can_write_to_container(elgg_get_logged_in_user_guid(), $container_guid)) {
	register_error(elgg_echo('googleapps:error:nopermission'));
	forward();
}

// Get google doc
$client = authorized_client(TRUE);
$document = googleapps_get_doc_from_id($client, $document_id);

// Update permissions if action is either public or domain
if ($action == 'public' || $action == 'domain') {
	googleapps_update_doc_permissions($client, $document['id'], $action) ;
}

// Share the doc
share_document($document, $description, $tags, $access_id, $container_guid);

echo elgg_view('googleapps/success', array('container_guid' => $container_guid));

forward(REFERER);
