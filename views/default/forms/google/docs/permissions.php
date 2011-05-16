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

$answer_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-permissions-answer',
	'name' => 'answer',
));

$container_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-container-guid',
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
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

$form_body = <<<HTML
	<h2>$heading</h2>
	<p><label>$message</label></p>
	$answer_input
	$container_input
	$grant_input $ignore_input
HTML;

echo $form_body;
