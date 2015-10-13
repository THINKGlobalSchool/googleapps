<?php
/**
 * Googleapps Document Browser JS library
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 *
 */

?>
//<script>
elgg.provide('elgg.googlefilepicker');

/**!
 * Google Drive File Picker Example
 * By Daniel Lo Nigro (http://dan.cx/)
 */
(function() {
	/**
	 * Initialise a Google Driver file picker
	 */
	var FilePicker = window.FilePicker = function(options) {
		var defaults = {
			onPickerApiLoaded: this._pickerApiLoaded,
			onDriveApiLoaded: this._driveApiLoaded
		}

		// Merge options
		var options = $.extend(defaults, options);

		// Config
		this.apiKey = options.apiKey;
		this.clientId = options.clientId;
		
		// Elements
		this.buttonEl = options.buttonEl;
		
		// Events
		this.onSelect = options.onSelect;
		this.onCancel = options.onCancel;

		// Other vars
		this.selectedDocument = null;

		// Check for button element
		if (this.buttonEl) {
			this.buttonEl.addEventListener('click', this.open.bind(this));		
	
			// Disable the button until the API loads, as it won't work properly until then.
			this.buttonEl.disabled = true;
		}

		// Load the drive API
		gapi.client.setApiKey(this.apiKey);
		gapi.client.load('drive', 'v2', options.onDriveApiLoaded.bind(this));
		google.load('picker', '1', { callback: options.onPickerApiLoaded.bind(this) });
	}
 
	FilePicker.prototype = {
		/**
		 * Open the file picker.
		 */
		open: function() {		
			// Check if the user has already authenticated
			var token = gapi.auth.getToken();
			if (token) {
				this._showPicker();
			} else {
				// The user has not yet authenticated with Google
				// We need to do the authentication before displaying the Drive picker.
				this._doAuth(false, function() { this._showPicker(); }.bind(this));
			}
		},
		
		/**
		 * Show the file picker once authentication has been done.
		 * @private
		 */
		_showPicker: function() {
			var accessToken = gapi.auth.getToken().access_token;

			// initial view for the first ViewGroup
			var docView = new google.picker.DocsView(google.picker.ViewId.DOCS);
			docView.setIncludeFolders(true);
			docView.setSelectFolderEnabled(true);

			this.picker = new google.picker.PickerBuilder().
				addViewGroup(
					new google.picker.ViewGroup(docView).
					addView(google.picker.ViewId.DOCUMENTS).
					addView(google.picker.ViewId.SPREADSHEETS).
					addView(google.picker.ViewId.DOCS_IMAGES_AND_VIDEOS).
					addView(google.picker.ViewId.PRESENTATIONS).
					addView(google.picker.ViewId.PDFS).
					addView(google.picker.ViewId.FOLDERS)).
				addViewGroup(new google.picker.ViewGroup(new google.picker.DocsUploadView())).
				setAppId(this.clientId).
				setOAuthToken(accessToken).
				setCallback(this._pickerCallback.bind(this)).
				//enableFeature(google.picker.Feature.MINE_ONLY).
				build().
				setVisible(true);
		},
		
		/**
		 * Called when a file has been selected in the Google Drive file picker.
		 * @private
		 */
		_pickerCallback: function(data) {
			if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
				var file = data[google.picker.Response.DOCUMENTS][0],
					id = file[google.picker.Document.ID],
					request = gapi.client.drive.files.get({
						fileId: id
					});

				// Store the picker document for use in the callback
				this.selectedDocument = file;
					
				request.execute(this._fileGetCallback.bind(this));
			} else if (data[google.picker.Response.ACTION] == google.picker.Action.CANCEL) {
				// Handle cancel action
				if (this.onCancel) {
					this.onCancel();
				}
			}
		},
		/**
		 * Called when file details have been retrieved from Google Drive.
		 * @private
		 */
		_fileGetCallback: function(respFile) {
			if (this.onSelect) {
				// Send the api response, along with the picker document
				this.onSelect(respFile, this.selectedDocument);
			}
		},
		
		/**
		 * Called when the Google Drive file picker API has finished loading.
		 * @private
		 */
		_pickerApiLoaded: function() {
			if (this.buttonEl) {
				this.buttonEl.disabled = false;
			}
		},
		
		/**
		 * Called when the Google Drive API has finished loading.
		 * @private
		 */
		_driveApiLoaded: function() {
			this._doAuth(true);
		},
		
		/**
		 * Authenticate with Google Drive via the Google JavaScript API.
		 * @private
		 */
		_doAuth: function(immediate, callback) {	
			gapi.auth.authorize({
				client_id: this.clientId + '.apps.googleusercontent.com',
				scope: 'https://www.googleapis.com/auth/drive',
				immediate: immediate
			}, callback);
		}
	};
}());