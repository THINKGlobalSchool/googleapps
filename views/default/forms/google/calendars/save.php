<?php
/**
 * Googleapps Save Calendars Form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

$title = elgg_extract('title', $vars);
$google_cal_feed = elgg_extract('google_cal_feed', $vars);
$text_color = elgg_extract('text_color', $vars);
$background_color = elgg_extract('background_color', $vars);
$access_id = elgg_extract('access_id', $vars, ACCESS_PUBLIC);
$guid = elgg_extract('guid', $vars);

//set up form components
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $title
));

$cal_feed_label = elgg_echo('googleapps:label:calendar_id');
$cal_feed_input = elgg_view('input/text', array(
	'name' => 'google_cal_feed',
	'value' => $google_cal_feed
));

$text_color_label = elgg_echo('googleapps:label:text_color_label');
$text_color_input = elgg_view('input/text', array(
	'name' => 'text_color',
	'value' => $text_color
));

$background_color_label = elgg_echo('googleapps:label:background_color_label');
$background_color_input = elgg_view('input/text', array(
	'name' => 'background_color',
	'value' => $background_color
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'value' => $access_id
));

$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));

if ($guid) {
	$save_button = elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'submit_button'));
} else {
	$save_button = elgg_view('input/submit', array('value' => elgg_echo('googleapps:label:add_cal_title'), 'class' => 'submit_button'));
}

//layout form
$form_body = <<<HTML
<p>
	<label for="calendar_title">$title_label</label>
	$title_input
</p>

<p>
	<label for="google_cal_feed">$cal_feed_label</label>
	$cal_feed_input
</p>	

<p>
	<label for="text_color">$text_color_label</label>
	$text_color_input
</p>

<p>
	<label for="text_color">$background_color_label</label>
	$background_color_input
</p>

<p>
	<label for="calendar_access_id">$access_label</label>
	$access_input
</p>

$guid_input

$save_button
HTML;

echo $form_body;