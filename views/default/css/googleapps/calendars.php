<?php
/** 
 * Elgg Google Apps Plugin
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 *
 * Generates the CSS for calendars
 *
 * This is not served up as a normal CSS file because we don't want it to be cached.
 */
header('Content-type: text/css', true);

$calendars = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'google_cal',
	'limit' => 0
));

foreach ($calendars as $calendar) {
	$bg_color = $calendar->background_color;
	$color = $calendar->text_color;
	$guid = $calendar->guid;
	
echo <<<___CSS
	.google-calendar-feed-$guid a,
	.google-calendar-feed-$guid,
	.google-calendar-feed-$guid .fc-event-skin {
		background-color: #$bg_color;
		border-color: #$bg_color;
		color: #$color
	}

	a.google-calendar-feed-$guid:hover {
		text-decoration: none !important;
	}

	.google-calendar-feed label {
		color: inherit;
	}

	.google-calendar-feed {
		border-radius: 5px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
	}

	.google-calendar-feed input {
		margin: 2px 2px 2px 5px !important;
	}

	.fc-agenda-slots td div {
		height: 45px;
	}
___CSS;
}