<?php
/**
 * Googleapps JS library
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

// Doc picker
$drive_api_client = elgg_get_plugin_setting('google_api_client_id', 'googleapps');
// Strip out the '.apps.googleusercontent.com'
$drive_api_client = str_replace(".apps.googleusercontent.com", '', $drive_api_client);
$drive_api_key = elgg_get_plugin_setting('google_drive_api_key', 'googleapps');

?>
//<script>
elgg.provide('elgg.google');

// Get php vars
elgg.google.DRIVE_API_CLIENT = "<?php echo $drive_api_client; ?>";
elgg.google.DRIVE_API_KEY = "<?php echo $drive_api_key; ?>";

// Ajax URL's
elgg.google.CHOOSER_URL = 'googleapps/docs/chooser';

elgg.google.apiLoaded = false;

/**
 * Main init function
 */
elgg.google.init = function() {	
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
	
	// Change handler for wiki menu orderby change
	$(document).delegate('#googlapps-wiki-orderby', 'change', elgg.google.wikiOrderByChange);
}

/**
 * Init google doc pickers
 */
elgg.google.initPickers = function() {
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
					// Change handler
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
					// Share new doc handler
					} else {
						if ($('#google-overlay-shade').length == 0) {
							$('body').prepend('<div id="google-overlay-shade" class="elgg-ajax-loader"></div>');

							$('#google-overlay-shade').fadeTo(300, 0.8);
						}

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
}

/**
 * Google Doc Submit handler
 */
elgg.google.docsSubmit = function(event) {
	var data = {};
	
	$($(this).serializeArray()).each(function (i, e) {
		data[e.name] = e.value;
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

	// Show spinner
	$(this).closest('.ui-dialog-content').html($(document.createElement('div')).addClass('elgg-ajax-loader mal'));

	elgg.action(this.action, {
		data: data,
		success: function(json) {
			// Return false on error. The action should spit out some useful information
			if (json.status == -1) {
				return false;
			}

			$('body').find('.ui-dialog').remove();

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
		}
	});
	
	event.preventDefault();
}

/**
 *  Change handler for wiki sort by change
 */
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
 * Inserts data into a text area
 *
 * @param string textAreaId 
 * @param string content
 *
 * @return void
 */
elgg.google.insert = function(textAreaId, content) {
	CKEDITOR.instances[textAreaId].insertHtml(content);
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

/**
 * Init todo submission google doc picker
 */
elgg.google.initTodoSubmissionPicker = function (hook, type, params, options) {
	// Init doc picker for todo submissions
	$('#add-googledoc').each(function() {
		var post_url = $(this).attr('href');

		var picker = new FilePicker({
				apiKey: elgg.google.DRIVE_API_KEY,
				clientId: elgg.google.DRIVE_API_CLIENT,
				buttonEl: this,
				onSelect: function(file) {
					// Build a json string for this google doc
					var google_json = '{"type": "googledoc", "icon": "' + file.iconLink +'", "title": "'+ file.title + '", "url": "' + file.alternateLink + '", "id": "' + file.id + '", "modified": "' + elgg.google.formatDate(new Date(file.modifiedDate)) + '"}';
					
					$('#submission-content-select').append(
						$('<option></option>').attr('selected', 'selected').val(google_json).html(file.title)
					);
					elgg.todo.submission.formDefault();
					$('#submission-notice-message').html(elgg.echo('googleapps:label:submissionnotice')).show();
				},
				onCancel: function() {
					elgg.todo.submission.formDefault();
				}
		});
	});
	return true;
}

elgg.google.addDriveButton = function(hook, type, params, value) {
	if (params.length) {
		params.each(function(idx) {
			var editor = $(this).ckeditorGet();

			editor.addCommand("insertDrive", { // create named command
			    exec: function(editor) {
			       
			    	var editor_id = editor.name;

					var picker = new FilePicker({
						apiKey: elgg.google.DRIVE_API_KEY,
						clientId: elgg.google.DRIVE_API_CLIENT,
						buttonEl: null,
						onSelect: function(file, documentInfo) {
							// We can only offer embeds if this is a google doc (slide, sheet, etc) or a folder
							if (documentInfo.type == 'folder' || documentInfo.type == 'document') {
								// Check if we want to embed the item itself (actual document) or just a link
								var documentType = documentInfo.type;

								if (documentType == 'folder') {
									var embedStringType = elgg.echo('googleapps:label:driveembedfolder');
								} else {
									var embedStringType = elgg.echo('googleapps:label:driveembedfile');
								}

								var $dialog = $(document.createElement('div'))
									.attr({
										'title': elgg.echo('googleapps:label:driveembedtype'),
										'id': 'google-drive-embed-type-dialog'
									});
								
								$dialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembeddesc'))).append('<br />');

								$dialog.append(
									$(document.createElement('button'))
										.addClass('elgg-button-submit pas mrs mtm google-drive-embed-insert')
										.append(elgg.echo('googleapps:label:driveembedlink'))
										.bind('click', {'file': file, 'documentInfo': documentInfo, 'editorId': editor_id}, elgg.google.insertDriveContent)
								);

								$dialog.append(
									$(document.createElement('button'))
										.addClass('elgg-button-submit pas mrs mtm google-drive-embed-actual')
										.append(elgg.echo('googleapps:label:driveembedactual', [embedStringType]))
										.bind('click', {'file': file, 'documentInfo': documentInfo, 'editorId': editor_id}, elgg.google.embedDriveContent)
								);
								

								$('body').append($dialog);

								$("#google-drive-embed-type-dialog").dialog({
									modal: true,
									draggable: false,
									resizable: false,
									closeOnEscape: false,
									open: function(event, ui) {	
										$(".ui-dialog-titlebar-close").remove();
									}
								});
							} else {
								// Got some other type of file (ie: uploaded pdf, video, mp3, word doc, etc)
								var fakeEvent = {};
								fakeEvent.data = {};
								fakeEvent.data.file = file;
								fakeEvent.data.documentInfo = documentInfo;
								fakeEvent.data.editorId = editor_id;
								elgg.google.insertDriveContent(fakeEvent);
							}
						}
					});

					// Auto open the picker (not bound to an element)
					picker.open();
			    }
			});

			// Add Google Drive button
			editor.ui.addButton('DriveButton', { // add new button and bind our command
			    label: elgg.echo("googleapps:label:insertcontent"),
			    command: 'insertDrive',
			    toolbar: 'custom2',
			    icon: elgg.get_site_url() + 'mod/googleapps/graphics/drive_icon.png'
			});

		});
	}
}

/** 
 * Generate insert content for docs/folders
 */ 
elgg.google.insertDriveContent = function(event) {
	// Update dialog content
	var $dialog = $('#google-drive-embed-type-dialog');
	$dialog.html($(document.createElement('div')).addClass('elgg-ajax-loader mal'));

	// Get event data
	var file = event.data.file;
	var documentInfo = event.data.documentInfo;
	var editor_id = event.data.editorId;

	elgg.action(elgg.get_site_url() + 'action/google/docs/insert', {
		data: {
			'doc_id': file.id
		},
		success: function(json) {

			// Nuke the dialog
			$dialog.dialog('destroy').remove();

			// Check for errors
			if (json.status == -1) {
				return false;
			}

			var $link = $(document.createElement('a'));
			$link.attr('href', file.alternateLink);
			$link.html(file.title);

			$(document).undelegate('.googleapps-docs-insert-success', 'click');
			$(document).delegate('.googleapps-docs-insert-success', 'click', function(event) {
				$(this).parents('.ui-dialog').remove();
				elgg.google.insert(editor_id, $link.get(0).outerHTML);
				event.preventDefault();
			});

			// Check insert status
			if (json.output.insert_status !== 1) {
				var title = elgg.echo('googleapps:label:permissions_warning_title');

				// Need to update permissions
				var dlg = $("<div></div>").html(json.output.form).dialog({
					width: 450,
					height: 'auto',
					modal: true,
					title: title,
					draggable: false,
					resizable: false,
					closeOnEscape: false
				});
			} else {
				// Good to go! Insert..
				elgg.google.insert(editor_id, $link.get(0).outerHTML);
			}
		}
	});
}

/** 
 * Generate embed content for docs/folders
 */ 
elgg.google.embedDriveContent = function(event) {

	// Update dialog content
	var $dialog = $('#google-drive-embed-type-dialog');
	$dialog.html($(document.createElement('div')).addClass('elgg-ajax-loader mal'));

	// Get event data
	var file = event.data.file;
	var documentInfo = event.data.documentInfo;
	var editor_id = event.data.editorId;

	// Going to allow adjusting dimensions
	var $optionsDialog = $(document.createElement('div'))
		.attr({
			'title': elgg.echo('googleapps:label:driveembedoptions'),
			'id': 'google-drive-embed-options-dialog'
		});

	// Check for a document or a folder
	if (documentInfo.type == 'folder') {
		// Got a folder, add inputs for selecting grid/list views
		$optionsDialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembedfolderstyle'))).append("<br />");
		

		// List
		$optionsDialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembedfolderlist')));
		$optionsDialog.append(
			$(document.createElement('input')).attr({
				'type': 'radio',
				'name': 'googleFolderStyle',
				'id': 'google-drive-embed-folder-list',
				'value': 'list',
				'checked': 'checked',
				'class': 'mls mrs'
			})
		);

		// Grid
		$optionsDialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembedfoldergrid')));
		$optionsDialog.append(
			$(document.createElement('input')).attr({
				'type': 'radio',
				'name': 'googleFolderStyle',
				'value': 'grid',
				'id': 'google-drive-embed-folder-grid',
				'class': 'mls mrs'
			})
		);

		$optionsDialog.append("<br />");
	}

	var defaultWidth = 650;
	var defaultHeight = 400;

	// Width input
	$optionsDialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembedwidth')))
	$optionsDialog.append(
		$(document.createElement('input')).attr({
			'id': 'google-drive-embed-width',
			'value': defaultWidth,
			'maxLength': 4,
			'class': 'mls mrs'
		})
	);

	// Height input
	$optionsDialog.append($((document).createElement('label')).append(elgg.echo('googleapps:label:driveembedheight')))
	$optionsDialog.append(
		$(document.createElement('input')).attr({
			'id': 'google-drive-embed-height',
			'value': defaultHeight,
			'maxLength': 4,
			'class': 'mls mrs'
		})
	).append("<br />");

	// Add finish button
	$optionsDialog.append(
		$(document.createElement('button'))
			.addClass('elgg-button-submit pas mrs mtm google-drive-embed-finish')
			.append(elgg.echo('googleapps:label:driveembedfinish'))
			.bind('click', {'file': file, 'documentInfo': documentInfo, 'editorId': editor_id}, function(event) {
				// Get width and height, and style if applicable
				var height = $('#google-drive-embed-height').val();
				var width = $('#google-drive-embed-width').val();
				var style = $('input[name=googleFolderStyle]:checked').val();

				// Show spinner
				$optionsDialog.html($(document.createElement('div')).addClass('elgg-ajax-loader mal'));

				// Fire the insert action to check permissions
				elgg.action(elgg.get_site_url() + 'action/google/docs/insert', {
					data: {
						'doc_id': file.id
					},
					success: function(json) {
						// Nuke the dialog
						$optionsDialog.dialog('destroy').remove();

						// Check for errors
						if (json.status == -1) {
							return false;
						}

						var $link = $(document.createElement('a'));
						$link.attr('href', file.alternateLink);
						$link.html(file.title);

						$(document).undelegate('.googleapps-docs-insert-success', 'click');
						$(document).delegate('.googleapps-docs-insert-success', 'click', function(event) {
							$(this).parents('.ui-dialog').remove();

							// Generate and insert embed code
							elgg.google.getAndInsertEmbedCode(file, documentInfo.type, height, width, style, editor_id);
			
							event.preventDefault();
						});

						// Check insert status
						if (json.output.insert_status !== 1) {
							var title = elgg.echo('googleapps:label:permissions_warning_title');

							// Need to update permissions
							var dlg = $("<div></div>").html(json.output.form).dialog({
								width: 450,
								height: 'auto',
								modal: true,
								title: title,
								draggable: false,
								resizable: false,
								closeOnEscape: false
							});
						} else {
							// Good to go! Insert..
							elgg.google.getAndInsertEmbedCode(file, documentInfo.type, height, width, style, editor_id);
						}
					}
				});
			})
	);

	// Remove old dialog
	$dialog.dialog('destroy').remove();

	// Append/show options dialog
	$('body').append($optionsDialog);
	$("#google-drive-embed-options-dialog").dialog({
		modal: true,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		open: function(event, ui) {	
			$(".ui-dialog-titlebar-close").remove();
		}
	});
}

// Generate and insert doc/folder embed code
elgg.google.getAndInsertEmbedCode = function(file, type, height, width, style, editor_id) {
	elgg.action(elgg.get_site_url() + 'action/google/docs/embed', {
		data: {
			'doc_id': file.id,
			'doc_embed_link': file.embedLink ? file.embedLink : null,
			'doc_type': type,
			'doc_embed_height': height, 
			'doc_embed_width': width,
			'doc_embed_folder_style': style
		},
		success: function(json) {

			// Check for errors
			if (json.status == -1) {
				return false;
			}
				
			elgg.google.insert(editor_id, json.output);

			return true;
		}
	});
}

elgg.register_hook_handler('init', 'ckeditor', elgg.google.addDriveButton);
elgg.register_hook_handler('init', 'system', elgg.google.init);

/**
 * Called when google js api is loaded
 */
function gapiLoaded() {
	// Register initPicker hook
	elgg.google.apiLoaded = true;


	// Trigger a hook here for plugins to do something when the google api is loaded
	elgg.trigger_hook('apiloaded', 'google');
	elgg.register_hook_handler('init', 'system', elgg.google.initPickers);
	elgg.register_hook_handler('submission_lightbox_loaded', 'todos', elgg.google.initTodoSubmissionPicker);
}