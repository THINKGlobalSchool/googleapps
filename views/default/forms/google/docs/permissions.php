<?php
/**
 * Googleapps update sharing permissions form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 */

// Get vars
$document_access = elgg_extract('access', $vars);
$document_info = elgg_extract('document_info', $vars);
$container_guid = elgg_extract('container_guid', $vars);
$options = elgg_extract('options', $vars);

$message = elgg_echo('googleapps:label:access_' . $document_access);

foreach ($options as $option) {
	switch ($option) {
		case 'domain':
			$buttons .=	elgg_view('output/url', array(
				'id' => 'googleapps-docs-permissions-update-domain',
				'text' => elgg_view_icon('share') . elgg_echo('googleapps:submit:domain'),
				'class' => 'permissions-update-input',
				'href' => '#',
				'data-action' => 'domain'
			));
			break;
		case 'public':
			$buttons .=	elgg_view('output/url', array(
				'id' => 'googleapps-docs-permissions-update-public',
				'text' => elgg_view_icon('share') . elgg_echo('googleapps:submit:public'),
				'class' => 'permissions-update-input',
				'href' => '#',
				'data-action' => 'public'
			));
			break;
		case 'ignore':
			$buttons .=	elgg_view('output/url', array(
				'id' => 'googleapps-docs-permissions-update-ignore',
				'text' => elgg_view_icon('delete') . elgg_echo('googleapps:submit:ignore'),
				'class' => 'permissions-update-input',
				'href' => '#',
				'data-action' => 'ignore'
			));
			break;
	}
}

// Hidden/document info inputs
$container_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-container-guid',
	'name' => 'container_guid',
	'value' => $container_guid,
));

$action_input = elgg_view('input/hidden', array(
	'id' => 'googleapps-docs-permissions-action',
	'name' => 'permissions_action',
	'value' => null
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
	<p><label>$message</label></p>
	<a href="" tabindex="1"></a>
	$container_input
	$action_input
	$buttons
	$doc_id_input
	$description_input
	$access_input
	$tags_input
HTML;

echo $form_body;
