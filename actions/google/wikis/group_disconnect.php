<?php
/**
 * Googleapps Group Wiki Disconnect action
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$wiki_guid = get_input('wiki_guid');
$group_guid = get_input('group_guid');

$group = get_entity($group_guid);

$referer = "groups/edit/{$group_guid}#other";

if (!$group->canEdit()) {
	register_error(elgg_echo('groups:permissions:error'));
	forward($referer);
}

$wiki = get_entity($wiki_guid);

if (elgg_instanceof($wiki, 'object', 'site')) {
	if (remove_entity_relationship($wiki->guid, GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP, $group->guid)) {
		system_message(elgg_echo('googleapps:success:groupwikidisconnected'));
	} else {
		register_error(elgg_echo('googleapps:error:wikidisconnectionfailed'));
	}
} else {
	register_error(elgg_echo('googleapps:error:invalidwiki'));
}

forward($referer);