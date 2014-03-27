<?php
/**
 * Googleapps JS library
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get some plugin settings
$oauth_sync_email = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
$oauth_sync_sites = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
$oauth_sync_docs = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');

$interval = elgg_get_plugin_setting('oauth_update_interval', 'googleapps');
$oauth_update_interval = $interval ? $interval : 3;

// Doc picker
$drive_api_client = elgg_get_plugin_setting('google_drive_api_client_id', 'googleapps');
$drive_api_key = elgg_get_plugin_setting('google_drive_api_key', 'googleapps');

?>
//<script>
elgg.provide('elgg.google');

// Get php vars
elgg.google.SYNC_EMAIL = "<?php echo $oauth_sync_email; ?>";
elgg.google.SYNC_SITES = "<?php echo $oauth_sync_sites; ?>";
elgg.google.SYNC_DOCS = "<?php echo $oauth_sync_docs; ?>";
elgg.google.UPDATE_INTERVAL = "<?php echo $oauth_update_interval; ?>";
elgg.google.DRIVE_API_CLIENT = "<?php echo $drive_api_client; ?>";
elgg.google.DRIVE_API_KEY = "<?php echo $drive_api_key; ?>";

// Ajax URL's
elgg.google.CHOOSER_URL = 'googleapps/docs/chooser';

elgg.google.init = function() {	
	// Init doc pickers
	$('.google-doc-picker').each(function() {
		$(this).bind('click', function(event) {
			event.preventDefault();
		});

		var post_url = $(this).attr('href');

		var picker = new FilePicker({
				apiKey: elgg.google.DRIVE_API_KEY,
				clientId: elgg.google.DRIVE_API_CLIENT,
				buttonEl: this,
				onSelect: function(file) {
					if ($(this.buttonEl).hasClass('google-doc-picker-change')) {
						// Set selected file info
						var $selected_container = $('#google-docs-selected');
						$selected_container.find('img#google-docs-selected-icon').attr('src', file.iconLink).show();
						$selected_container.find('input[name="document_id"]').val(file.id);

						var $link = $(document.createElement('a'));
						$link.attr('href', file.alternateLink);
						$link.attr('target', '_blank');
						$link.html(file.title);

						$selected_container.find('span#google-docs-selected-title').html($link);

						var friendly_date = new Date(file.modifiedDate);

						$selected_container.find('span#google-docs-selected-modified').html(elgg.google.formatDate(friendly_date)).show();
						$selected_container.find('#google-docs-selected-inner').css('display','inline-block');

						if ($(this.buttonEl).hasClass('elgg-button')) {
							$(this.buttonEl)
								.removeClass('elgg-button')
								.removeClass('elgg-button-action')
								.html(elgg.echo('googleapps:label:change'));
						}
					} else {
						var form = document.createElement("form");
						form.setAttribute("method", 'post');
						form.setAttribute("action", post_url);

						var friendly_date = new Date(file.modifiedDate);

						var params = {
							'icon': file.iconLink,
							'document_id': file.id,
							'title': file.title,
							'modified': elgg.google.formatDate(friendly_date),
							'link': file.alternateLink
						};

						for(var key in params) {
							if(params.hasOwnProperty(key)) {
								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", "hidden");
								hiddenField.setAttribute("name", key);
								hiddenField.setAttribute("value", params[key]);

								form.appendChild(hiddenField);
							}
						}

						document.body.appendChild(form);
	   					form.submit();
					}
				}
		});
	});

	// Register interval for future updates
	//setInterval(elgg.google.updateGoogleApps, (elgg.google.UPDATE_INTERVAL * 60 * 1000));
	
	// Google Docs Form Stuff
	$('.permissions-update-input').live('click', function(event) {
		var $form = $(this).closest('form');

		$form.find('#googleapps-docs-permissions-action').val($(this).data('action'));
		$form.trigger('submit');
		event.preventDefault();
	})
	
	// Match permissions UI
	$('#google-docs-match-permissions').change(function() {
		if ($(this).val() == 0) {
			$('#google-docs-access-id').removeAttr('disabled');
			$('#google-docs-access-id-label').removeAttr('style');
		} else {
			$('#google-docs-access-id').attr('disabled', 'disabled');
			$('#google-docs-access-id-label').attr('style', 'color: #999999');
		}
	});
	
	// Bind docsSubmit function to forms
	$('#google-docs-update-permissions').live('submit', elgg.google.docsSubmit);
	$('#google-docs-share-form').live('submit', elgg.google.docsSubmit);
	
	// Switch share form click event (makes tabs clickable)
	$('.googleapps-docs-share-switch').live('click', elgg.google.showTab);
	
	// Change handler for wiki menu orderby change
	$(document).delegate('#googlapps-wiki-orderby', 'change', elgg.google.wikiOrderByChange);
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
				if (json.output.mail_count && json.output.mail_count != 0) {
					anchor.append("<span class='messages-new'>" + json.output.mail_count + "</span>")
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

// Submit handler
elgg.google.docsSubmit = function(event) {			
	var data = {};
	
	$($(this).serializeArray()).each(function (i, e) {
		data[e.name] = e.value;
		// TinyMCE does some voodoo magic.. need to account for that
		if (e.name == 'description' && typeof(tinyMCE) !== 'undefined') {
			var description = tinyMCE.get('description');
			if (description) {
				data[e.name] = description.getContent();
			}
		}
	});

	// Switch title based on action
	if (this.action.lastIndexOf('share') != -1) {
		var show_close = true;
		var title = elgg.echo('googleapps:label:permissions_warning_title');
	}

	if (this.action.lastIndexOf('permissions') != -1) {
		var show_close = false;
		var title = elgg.echo('googleapps:success');
	}

	elgg.action(this.action, {
		data: data,
		success: function(json) {			
			// Return false on error. The action should spit out some useful information
			if (json.status == -1) {
				return false;
			}

			// Show dialog
			var dlg = $("<div></div>").html(json.output).dialog({
				width: 450,
				height: 'auto',
				modal: true,
				title: title,
				draggable: false,
				resizable: false,
				closeOnEscape: false,
				open: function(event, ui) {
					if (!show_close) {
						$(".ui-dialog-titlebar-close").remove();
					}
				}
			});
			
			dlg.find('form').submit(function () {
				dlg.parents('.ui-dialog').remove();
			});
		}
	});
	
	event.preventDefault();
}

// Tab click handler, shows the tab id supplied as HREF
elgg.google.showTab = function(event) {
	// Remove selected states
	$(this).parent().parent().find('li').removeClass('elgg-state-selected');
	
	// Add selected state to this item
	$(this).parent().addClass('elgg-state-selected');
	
	// Hide all the divs
	$('.googleapps-docs-share-div').hide()
	
	// Show this HREF's div
	$($(this).attr('href')).show();
	
	event.preventDefault();
}

// Change handler for wiki sort by change
elgg.google.wikiOrderByChange = function(event) {
	var order_by = $(this).val();
	var href = $('#googleapps-wiki-order').attr('href');
	var location;
	
	// Switch ASC/DESC
	if (href.search('ASC') != -1) {
		href = href.replace('ASC', 'DESC');
	} else if (href.search('DESC') != -1) {
		href = href.replace('DESC', 'ASC');
	}

	// Set sort by
	if (order_by == 'alpha') {
		location = href.replace('updated', order_by);
	} else {
		location = href.replace('alpha', order_by);		
	}
	
	window.location = (location);
	
	event.preventDefault();
}

/**
 * Helper date format function
 */
elgg.google.formatDate = function(date) {
	var m_names = new Array(
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", 
		"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
	);

	var curr_date = date.getDate();
	var curr_month = date.getMonth();
	var curr_year = date.getFullYear();
	return curr_date + " " + m_names[curr_month] 
	+ " " + curr_year;
}

elgg.register_hook_handler('init', 'system', elgg.google.init);