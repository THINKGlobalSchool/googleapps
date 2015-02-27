<?php
/**
 * Elgg Google Apps Calendar admin page
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

// want the colors
elgg_load_css('elgg.google.calendars');

// show the existing calendars
$calendars = elgg_get_entities(array(
	'type' => 'object',
	'subtype'=>'google_cal'
));

if ($calendars) {
	echo '<ul class="elgg-google-calendar-admin">';
	foreach($calendars as $cal) {
		echo elgg_view_entity($cal);
	}
	echo '</ul>';
}  else {
	echo '<p>' . elgg_echo('googleapps:label:no_calendars') . '</p>';
}

// show a new form
$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'google_cal')) {
	$vars = google_calendars_prepare_form_vars($entity);
} else {
	$vars = google_calendars_prepare_form_vars();
}

echo elgg_view_form('google/calendars/save', array(), $vars);