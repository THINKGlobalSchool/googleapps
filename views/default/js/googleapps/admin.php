<?php
/**
 * Googleapps Admin JS library
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
//<script>
elgg.provide('elgg.google_admin');

elgg.google_admin.init = function() {	
	// Delegate click handler for admin menu items
	$(document).delegate('.googleapps-admin-menu-item', 'click', elgg.google_admin.adminMenuClick);

	// Delegate click handler for admin run sync cron
	$(document).delegate('#googleapps-run-sync-cron', 'click', elgg.google_admin.adminRunSyncCronClick);

	// Delegate click handler for admin run group cron
	$(document).delegate('#googleapps-run-group-cron', 'click', elgg.google_admin.adminRunGroupCronClick);
	
	// Delegate click handler for admin reset site activity
	$(document).delegate('#googleapps-reset-sites-activity', 'click', elgg.google_admin.adminResetSiteActivityClick);
	
	//$(document).delegate('#googleapps-test-one', 'click', elgg.google_admin.adminTestOneClick);
		
	//$(document).delegate('#googleapps-test-two', 'click', elgg.google_admin.adminTestTwoClick);
}

// Click handler for admin menu items
elgg.google_admin.adminMenuClick = function(event) {
	$('.googleapps-admin-menu-item').parent().removeClass('elgg-state-selected');
	$(this).parent().addClass('elgg-state-selected');

	$('.googleapps-menu-container').hide();
	
	// Hide 'save' button
	if ($(this).attr('href') != "#googleapps-admin-settings") {
		$('form#googleapps-settings').find('input.elgg-button-submit').hide();
	} else {
		$('form#googleapps-settings').find('input.elgg-button-submit').show();
	}
	
	$($(this).attr('href')).show();
	
	event.preventDefault();
}

// Admin run cron click handler 
elgg.google_admin.adminRunSyncCronClick = function(event) {
	$("#googleapps-cron-output").html("<div class='elgg-ajax-loader'></div>");
	elgg.get(elgg.get_site_url() + "googleapps/admin/wiki_cron", {
		success: function(data) {
			$("#googleapps-cron-output").html(data);
		},
		error: function() {
			$("#googleapps-cron-output").html("There was an error loading output");
		}
	});
	event.preventDefault();
}

// Admin run cron click handler 
elgg.google_admin.adminRunGroupCronClick = function(event) {
	$("#googleapps-cron-output").html("<div class='elgg-ajax-loader'></div>");
	elgg.get(elgg.get_site_url() + "googleapps/admin/wiki_group_cron", {
		success: function(data) {
			$("#googleapps-cron-output").html(data);
		},
		error: function() {
			$("#googleapps-cron-output").html("There was an error loading output");
		}
	});
	event.preventDefault();
}

// Admin run cron click handler 
elgg.google_admin.adminResetSiteActivityClick = function(event) {
	if (confirm('Are you sure?')) {
		$("#googleapps-cron-output").html("<div class='elgg-ajax-loader'></div>");
		elgg.get(elgg.get_site_url() + "googleapps/admin/wiki_reset_activity", {
			success: function(data) {
				$("#googleapps-cron-output").html(data);
			},
			error: function() {
				$("#googleapps-cron-output").html("There was an error loading output");
			}
		});
	}
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.google_admin.init);