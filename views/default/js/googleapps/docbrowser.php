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
	$(document).delegate('#googledocsbrowser-next', 'click', elgg.googledocbrowser.nextClick);

	// Previous link
	$(document).delegate('#googledocsbrowser-previous', 'click', elgg.googledocbrowser.previousClick);
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
		elgg.googledocbrowser.container.html('Sorry, no docs');
	}
}

// Populates the document browser
elgg.googledocbrowser.populate = function(offset) {
	elgg.googledocbrowser.container.html('');
	
	// Initial limit
	var limit = offset + elgg.googledocbrowser.LIMIT;
	
	// Make sure our loop limit is less than the total count of docs
	if (limit > elgg.googledocbrowser.documents.length) {
		limit = elgg.googledocbrowser.documents.length;
	}
	
	// Loop over and display docs
	for (i = offset; i < limit; i++) {
		elgg.googledocbrowser.container.append(elgg.googledocbrowser.documents[i].title);
		elgg.googledocbrowser.container.append("<br />");
	}
	
	// Don't display previous unless we have a proper offset
	if (offset != 0) {
		var $prev_link = $(document.createElement('a'));
		$prev_link.html('<< prev ');
		$prev_link.attr('id', 'googledocsbrowser-previous');
		$prev_link.attr('href', '#');
		elgg.googledocbrowser.container.append($prev_link);
	}

	// If we have a start key, or more items to display show next link
	if (elgg.googledocbrowser.start_key || offset + elgg.googledocbrowser.LIMIT < elgg.googledocbrowser.documents.length) {
		var $next_link = $(document.createElement('a'));
		$next_link.html('next >>');
		$next_link.attr('id', 'googledocsbrowser-next');
		$next_link.attr('href', '#');
		elgg.googledocbrowser.container.append($next_link);
	}
}

// Push documents onto the document array
elgg.googledocbrowser.pushDocuments = function(doc_array) {
	// Quick and easy array append
	elgg.googledocbrowser.documents.push.apply(elgg.googledocbrowser.documents, doc_array);
}

// Click handler for next button
elgg.googledocbrowser.nextClick = function(event) {
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
	// Just going backwards through the doc array, don't need to try and load any more
	elgg.googledocbrowser.current_offset -= elgg.googledocbrowser.LIMIT;  // Decrease offset
	elgg.googledocbrowser.populate(elgg.googledocbrowser.current_offset); // Populate browser
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.googledocbrowser.init);