<?php
/**
 * Googleapps share doc action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

$document_guid = get_input('guid', null);
$document = get_entity($document_guid);

if (elgg_instanceof($document, 'object', 'shared_doc') && $document->canEdit()) {
	$container = get_entity($document->container_guid);
	if ($document->delete()) {
		system_message(elgg_echo('googleapps:success:delete'));
		if (elgg_instanceof($container, 'group')) {
			forward("googleapps/docs/group/$container->guid/owner");
		} else {
			forward("googleapps/docs/owner/$container->username");
		}
	} else {
		register_error(elgg_echo('googleapps:error:delete'));
	}
} else {
	register_error(elgg_echo('googleapps:error:notfound'));
}

forward(REFERER);
