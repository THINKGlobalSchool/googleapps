<?php
/**
 * Elgg Google Apps Delete a shared calendar
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'google_cal')) {
	$entity->delete();
} else {
	register_error(elgg_echo('googleapps:error:calendar_not_found'));
}

forward(REFERER);