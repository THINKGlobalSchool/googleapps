<?php
/**
 * Googleapps Document Browser JS library
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
elgg.provide('elgg.googledocbrowser');

// Browser endpoint
elgg.googledocbrowser.DOCS_ENDPOINT = elgg.get_site_url() + "googleapps/docs/browser";

// Limit per page
elgg.googledocbrowser.LIMIT = 10;

// Local vars
elgg.googledocbrowser.documents = [];
elgg.googledocbrowser.start_key = null;
elgg.googledocbrowser.container = null;
elgg.googledocbrowser.current_offset = 0;

// Init
elgg.googledocbrowser.init = function() {
	// Set which container we're using for the browser
	elgg.googledocbrowser.container = $('#google-docs-browser');

	// Do an inital document load, calling the initial callback
	elgg.googledocbrowser.loadDocuments(elgg.googledocbrowser.initialLoadCallBack);

	// Next link
	$(document).delegate('a#googledocsbrowser-next', 'click', elgg.googledocbrowser.nextClick);

	// Previous link
	$(document).delegate('a#googledocsbrowser-previous', 'click', elgg.googledocbrowser.previousClick);
}

// Loads more documents from the api
elgg.googledocbrowser.loadDocuments = function(callback) {
	var endpoint = elgg.googledocbrowser.DOCS_ENDPOINT;
	
	if (elgg.googledocbrowser.start_key) {	
		endpoint += "?start_key=" + elgg.googledocbrowser.start_key;
	}
	
	elgg.getJSON(endpoint, {
		success: function(data) {
			elgg.googledocbrowser.pushDocuments(data.list);
			elgg.googledocbrowser.start_key = data.start_key;
			callback();
		},
		error: function() {
			//  
		}
	});
}

// Callback for intial document load
elgg.googledocbrowser.initialLoadCallBack = function() {
	// We have docs
	if (elgg.googledocbrowser.documents.length > 0) {
		elgg.googledocbrowser.populate(0);
	} else {
		var $tbody = elgg.googledocbrowser.container.find('#google-docs-table > tbody');
		var message = elgg.echo('googleapps:label:nodocuments');
		$tbody.html("<tr><td colspan='4'><div class='google-docs-none'>" + message + "</div></td></tr>");
	}
}

// Populates the document browser
elgg.googledocbrowser.populate = function(offset) {
	var $table = elgg.googledocbrowser.container.find('#google-docs-table');
	var $tbody = $table.find('tbody');
	var $paging = elgg.googledocbrowser.container.find('#google-docs-paging');
	$tbody.html('');
	$paging.html('');
	
	// Initial limit
	var limit = offset + elgg.googledocbrowser.LIMIT;
	
	// Make sure our loop limit is less than the total count of docs
	if (limit > elgg.googledocbrowser.documents.length) {
		limit = elgg.googledocbrowser.documents.length;
	}

	// Loop over and display docs
	for (i = offset; i < limit; i++) {
		// Creating table elements
		var $tr = $(document.createElement('tr'));
		
		var $td = $(document.createElement('td'));
		$td.addClass('google-docs-table-select');
		
		// Document input
		var $input = $(document.createElement('input'));
		$input.attr({
			type: 'radio',
			value: elgg.googledocbrowser.documents[i].id,
			name: 'document_id'
		});
		
		$td.append($input);
		$tr.append($td);
		
		$td = $(document.createElement('td'));
		$td.addClass('google-docs-table-name');
		
		var $icon = $(document.createElement('span'));
		$icon.addClass('document-icon');
		$icon.addClass(elgg.googledocbrowser.documents[i].type);
		
		var $link = $(document.createElement('a'));
		$link.attr('href', elgg.googledocbrowser.documents[i].href);
		$link.text(elgg.googledocbrowser.documents[i].title);
		
		$td.append($icon);
		$td.append($link);
		$tr.append($td);
		
		$td = $(document.createElement('td'));
		$td.addClass('google-docs-table-collaborators');
		$td.text(elgg.googledocbrowser.formatPermissions(elgg.googledocbrowser.documents[i].collaborators));
		
		$tr.append($td);
		
		$td = $(document.createElement('td'));
		$td.addClass('google-docs-table-updated');
		
		var updated = $.datepicker.formatDate('yy-mm-dd', new Date(elgg.googledocbrowser.documents[i].updated * 1000));
		
		$td.text(updated);
		
		$tr.append($td);
		
		$tbody.append($tr);
	}
	
	// Don't display previous unless we have a proper offset
	if (offset != 0) {
		var $prev_link = $(document.createElement('a'));
		$prev_link.html(elgg.echo('googleapps:label:previouspage'));
		$prev_link.attr('id', 'googledocsbrowser-previous');
		$prev_link.attr('href', '#');
		$paging.append($prev_link);
	}

	// If we have a start key, or more items to display show next link
	if (elgg.googledocbrowser.start_key || offset + elgg.googledocbrowser.LIMIT < elgg.googledocbrowser.documents.length) {
		var $next_link = $(document.createElement('a'));
		$next_link.html(elgg.echo('googleapps:label:nextpage'));
		$next_link.attr('id', 'googledocsbrowser-next');
		$next_link.attr('href', '#');
		$paging.append($next_link);
	}
}

// Push documents onto the document array
elgg.googledocbrowser.pushDocuments = function(doc_array) {
	// Quick and easy array append
	elgg.googledocbrowser.documents.push.apply(elgg.googledocbrowser.documents, doc_array);
}

// Click handler for next button
elgg.googledocbrowser.nextClick = function(event) {
	elgg.googledocbrowser.showLoader();
	$(this).replaceWith($("<span id='googledocsbrowser-next'>" + $(this).html() + "</span>"));

	// Only load more docs if we're at the limit, and we have a start key 
	if (elgg.googledocbrowser.start_key 
		&& (elgg.googledocbrowser.current_offset + elgg.googledocbrowser.LIMIT) == elgg.googledocbrowser.documents.length) 
	{
		// Load more docs from the api, then populate
		elgg.googledocbrowser.loadDocuments(function() {
			elgg.googledocbrowser.current_offset += elgg.googledocbrowser.LIMIT;  // Increase offset
			elgg.googledocbrowser.populate(elgg.googledocbrowser.current_offset); // Populate browser
		});
	} else {
		// Just paging though loaded docs
		elgg.googledocbrowser.current_offset += elgg.googledocbrowser.LIMIT;  // Increase offset
		elgg.googledocbrowser.populate(elgg.googledocbrowser.current_offset); // Populate browser
	}

	event.preventDefault();
}

// Click handler for previous button
elgg.googledocbrowser.previousClick = function(event) {
	elgg.googledocbrowser.showLoader();
	$(this).replaceWith($("<span id='googledocsbrowser-previous'>" + $(this).html() + "</span>"));

	// Just going backwards through the doc array, don't need to try and load any more
	elgg.googledocbrowser.current_offset -= elgg.googledocbrowser.LIMIT;  // Decrease offset
	elgg.googledocbrowser.populate(elgg.googledocbrowser.current_offset); // Populate browser

	event.preventDefault();
}

// Show the ajax loader animation on the browser table
elgg.googledocbrowser.showLoader = function() {
	var $tbody = elgg.googledocbrowser.container.find('#google-docs-table > tbody');
	$tbody.html("<tr><td colspan='4'><div class='elgg-ajax-loader'></div></td></tr>");
}

// Helper function to create a nicely formatted permission string
elgg.googledocbrowser.formatPermissions = function(collaborators) {
	if(collaborators instanceof Array) {
		collaborators = collaborators.length;
	}

	var string = '';

	switch (collaborators) {
		case 'everyone_in_domain' :
			string = 'Everyone in domain';
			break;
		case 'public':
		 	string = 'Public';
			break;
		default:
			if(collaborators > 1) {
				string = (collaborators -1) + ' collaborators'; // minus owner
			} else {
				string = 'me';
			}
			break;
	}
	return string;
}

elgg.register_hook_handler('init', 'system', elgg.googledocbrowser.init);