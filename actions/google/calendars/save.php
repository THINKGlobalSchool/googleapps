<?php
/**
 * Elgg Google Apps Save a shared calendar
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$guid = get_input('guid');

elgg_make_sticky_form('google-calendar-save');

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'google_cal')) {
		$cal = $entity;
	} else {
		register_error(elgg_echo('googleapps:error:calendar_not_found'));
		forward($_SERVER['HTTP_REFERER']);
	}
} else {
	$cal = new ElggObject();
	$cal->subtype = 'google_cal';
}

$title = strip_tags(get_input('title'));
$google_cal_feed = get_input('google_cal_feed');
$text_color = get_input('text_color');
$background_color = get_input('background_color');
$access_id = get_input('access_id');

if (!($title && $google_cal_feed)) {
	register_error(elgg_echo('googleapps:error:missing_fields'));
	forward(REFERER);
}

$cal->title = $title;
$cal->google_cal_feed = $google_cal_feed;
$cal->text_color = $text_color;
$cal->background_color = $background_color;
$cal->access_id = $access_id;

if ($cal->save()) {
	elgg_clear_sticky_form('google-calendar-save');
	system_message(elgg_echo('googleapps:success:savecalendar'));
} else {
	register_error(elgg_echo('googleapps:error:savecalendar'));
}

forward(REFERER);