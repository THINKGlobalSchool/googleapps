<?php
/**
 * Google docs generate embed action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

// Get inputs
$document_id = get_input('doc_id');
$document_embed_link = get_input('doc_embed_link');
$document_type = get_input('doc_type');
$document_embed_height = get_input('doc_embed_height', FALSE);
$document_embed_width = get_input('doc_embed_width', FALSE);
$document_embed_style = get_input('doc_embed_folder_style', FALSE);

if ($document_type == 'folder') {
	echo elgg_view('googleapps/embedfolder', array(
		'id' => $document_id,
		'height' => $document_embed_height,
		'width' => $document_embed_width,
		'style' => $document_embed_style
	));
} else {
	echo elgg_view('googleapps/embedfile', array(
		'embed_link' => $document_embed_link,
		'height' => $document_embed_height,
		'width' => $document_embed_width
	));
}