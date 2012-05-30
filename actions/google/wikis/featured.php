<?php
/**
 * Googleapps feature a wiki
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$guid = get_input('guid');
$action = get_input('action_type');

$site = get_entity($guid);

if (!elgg_instanceof($site, 'object', 'site')) {
	register_error(elgg_echo('googleapps:error:invalidwiki'));
	forward(REFERER);
}

//get the action, is it to feature or unfeature
if ($action == "feature") {
	$site->featured_wiki = "yes";
	system_message(elgg_echo('googleapps:success:feature'));
} else {
	$site->featured_wiki = "no";
	system_message(elgg_echo('googleapps:success:unfeature'));
}

forward(REFERER);
