<?php
/**
 * Googleapps edit doc action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$guid = get_input('guid');
$title = get_input('title');
$description = get_input('description');
$tags = string_to_tag_array(get_input('tags')); 

elgg_make_sticky_form('google-docs-edit-form');

$google_doc = get_entity($guid);
if (!elgg_instanceof($google_doc, 'object', 'shared_doc') || !$google_doc->canEdit()) {
	register_error(elgg_echo('googleapps:error:share_doc_save'));
	forward(REFERER);
}

if (!$title) {
	register_error(elgg_echo('googleapps:error:requiredfields'));
	forward(REFERER);
}

$google_doc->title = $title;
$google_doc->description = $description;
$google_doc->tags = $tags;

if ($google_doc->save()) {
	// remove sticky form entries
	elgg_clear_sticky_form('google-docs-edit-form');
	
	system_message(elgg_echo('googleapps:success:share_doc_save'));
	if (elgg_instanceof($google_doc->getContainerEntity(), 'group')) {
		forward("googleapps/docs/group/{$google_doc->getContainerGUID()}/owner");
	} else {
		forward('googleapps/docs/all');
	}
	
} else {
	register_error(elgg_echo('googleapps:error:share_doc_save'));
	forward(REFERER);
}



