<?php
/**
 * Googleapps update sharing permissions form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 */

$heading = elgg_echo('googleapps:label:action_required');
$message = elgg_echo('googleapps:error:document_permissions_update');

// Get local document info
$document_info = elgg_extract('document_info', $vars);

// Inputs
$answer_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-permissions-answer',
	'name' => 'answer',
));

$grant_input = elgg_view('input/submit', array(
	'id' => 'googleapps-docs-permissions-update-grant',
	'name' => 'googleapps_docs_permissions_update_grant',
	'value' => elgg_echo('googleapps:submit:grant'),
	'class' => 'permissions-update-input elgg-button-action',
));

$ignore_input = elgg_view('input/submit', array(
	'id' => 'googleapps-docs-permissions-update-ignore',
	'name' => 'googleapps_docs_permissions_update_ignore',
	'value' => elgg_echo('googleapps:submit:ignore'),
	'class' => 'permissions-update-input elgg-button-action',
));

// Hidden/document info inputs
$container_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-container-guid',
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
));

$doc_id_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-id',
	'name' => 'doc_id',
	'value' => $document_info['doc_id'],
));

$description_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-description',
	'name' => 'description',
	'value' => $document_info['description'],
));

$access_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-access',
	'name' => 'access',
	'value' => $document_info['access'],
));

$tags_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-tags',
	'name' => 'tags',
	'value' => $document_info['tags'],
));

$form_body = <<<HTML
	<h2>$heading</h2>
	<p><label>$message</label></p>
	$answer_input
	$container_input
	$grant_input $ignore_input
	$doc_id_input
	$description_input
	$access_input
	$tags_input
HTML;

echo $form_body;
