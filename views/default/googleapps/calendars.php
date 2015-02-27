<?php
/**
 * Elgg Google Apps Draw calendars
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

elgg_load_js('tgs.fullcalendar');
elgg_load_js('elgg.google.gcal');
elgg_load_js('elgg.google.calendars');

elgg_load_css('tgs.fullcalendar');
elgg_load_css('elgg.google.calendars');

//build javascript array from calendar entities
$calendars = $vars['calendars'];
$info = array();
foreach($calendars as $calendar) {
	$info[$calendar->getGUID()] = array(
		'url' => $calendar->google_cal_feed,
		'text_color' => $calendar->text_color,
		'background_color' => $calendar->background_color,
		'display' => true
	);
}

$json = json_encode($info);
?>
<script type='text/javascript'>
	// the elgg JS object is already loaded at this point, so we can do this without wrapping in
	// $() or hooks.
	// kids, don't try this at home.
	elgg.provide('elgg.google.calendars');

	elgg.google.calendars.calendars = <?php echo $json; ?>;
</script>

<div class="mtl" id="elgg-google-calendars"></div>
<!-- For debug><p><a href="javascript:setCalColors()">Set Colors</a></p> -->
