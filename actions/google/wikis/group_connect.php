<?php
/**
 * Googleapps Group Wiki Connect action
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
	if (add_entity_relationship($wiki->guid, GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP, $group->guid)) {
		$time = time();
		$wiki->connected_time = $time;
		$wiki->last_activity_time = $time; // Set last activity to connection time so we don't pull ancient entries
		system_message(elgg_echo('googleapps:success:groupwikiconnected'));
	} else {
		register_error(elgg_echo('googleapps:error:wikiconnectionfailed'));
	}
} else {
	register_error(elgg_echo('googleapps:error:invalidwiki'));
}

forward($referer);