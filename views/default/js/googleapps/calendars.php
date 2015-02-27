<?php
/**
 * Elgg Google Apps Calendars JS
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */
?>
//<script>

elgg.provide('elgg.google.calendars');

elgg.google.calendars.init = function() {
	// calendars are stored in elgg.google.calendars.calendars.
	elgg.google.calendars.buildCalendar(elgg.google.calendars.getCalendars());

	$('.elgg-google-calendar-toggler').live('click', elgg.google.calendars.toggleCalendar);
}

/**
 * Returns the calendars
 *
 * @return {Object}
 */
elgg.google.calendars.getCalendars = function() {
	return elgg.google.calendars.calendars;
}

/**
 * Builds the calendar from a JSON object
 */
elgg.google.calendars.buildCalendar = function(calendars) {
	$('#elgg-google-calendars').fullCalendar({
		weekMode: 'liquid',
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		eventSources: elgg.google.calendars.buildSources(calendars)
	});
}

/**
 * Build array of Full Calendar gcal sources with unique class names
 *
 * @param {Object} The calendars object
 * @return {Array}
 */
elgg.google.calendars.buildSources = function(calendars) {
	var sources = [];
	var i = 0;
	$.each(calendars, function(k, v) {
		if (v.display) {
			sources[i] = $.fullCalendar.spotgcalFeed(v.url, {className: 'google-calendar-feed-' + k});
			i++;
		}
	});

	return sources;
}

/*
 * Toggle calendar requested and rebuild display
 */
elgg.google.calendars.toggleCalendar = function() {
	var guid = $(this).attr('id').split('-')[2];
	var calendars = elgg.google.calendars.getCalendars();

	calendars[guid]['display'] = $(this).is(':checked');

	$('#elgg-google-calendars').empty();
	elgg.google.calendars.buildCalendar(calendars);
}

elgg.register_hook_handler('init', 'system', elgg.google.calendars.init);