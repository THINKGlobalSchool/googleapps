<?php
/**
 * Googleapps JS library
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get some plugin settings
$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
$oauth_sync_docs = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');

$interval = elgg_get_plugin_setting('oauth_update_interval', 'googleapps');
$oauth_update_interval = $interval ? $interval : 3;

?>
//<script>
elgg.provide('elgg.google');

// Get php vars
elgg.google.SYNC_EMAIL = "<?php echo $oauth_sync_email; ?>";
elgg.google.SYNC_SITES = "<?php echo $oauth_sync_sites; ?>";
elgg.google.SYNC_DOCS = "<?php echo $oauth_sync_docs; ?>";
elgg.google.UPDATE_INTERVAL = "<?php echo $oauth_update_interval; ?>";

elgg.google.init = function() {	
	$(function() {	
		// Do an initial update
		elgg.google.updateGoogleApps();
		
		// Register interval for future updates
		setInterval(elgg.google.updateGoogleApps, (elgg.google.UPDATE_INTERVAL * 60 * 1000));
		
		// Google Docs Form Stuff (need to test this)
		$('.permissions-update-input').live('click', function(el) {
			// Probably need a this or something
			alert('??');
			el.form.answer.value = el.value;
		})

	});
}

// Call the oauth_update action 
elgg.google.updateGoogleApps = function() {	
	elgg.action('google/auth/oauth_update', {
		error: function(e) {
			console.log(e);
		},
		success: function(json) {
			// Check if grabbing email count is enabled
			if (elgg.google.SYNC_EMAIL == 'yes') {
				var anchor = $('.google-email-container a');
				
				// Nuke the messages-new span if no messages
				anchor.find('.messages-new').remove();
				
				// Add mail count if it exists
				if (json.mail_count != 0) {
					anchor.append("<span class='messages-new'>" + json.mail_count + "</span>")
				} 
			}
			
			// Do something with docs 
			if (elgg.google.SYNC_DOCS == 'yes') {
				/*
				The original oauth_script calls the following code.. I'm not sure what
				it was indended to do, as I haven't seen widgets working. Its calling a couple 
				functions that live in that view (widgets/google_docs).. I'm leaving that code there
				and this here.. just in case
		
				
				var widget_id = search_widget_id($("#google_docs_widget"));
				setTimeout(function(){update_widget(widget_id, '<?=$user->username?>')}, '100');
				*/
			}
		}
	});	
}

elgg.register_hook_handler('init', 'system', elgg.google.init);
//</script>