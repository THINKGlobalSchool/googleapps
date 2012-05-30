<?php
/**
 * Googleapps Group Wiki profile box
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$group = elgg_extract('entity', $vars);

// Current connected wiki's
$options = array(
	'type' => 'object',
	'subtype' => 'site',
	'limit' => 0,
	'relationship' => GOOGLEAPPS_GROUP_WIKI_RELATIONSHIP, 
	'relationship_guid' => $group->guid, 
	'inverse_relationship' => TRUE,
);

$wikis = elgg_get_entities_from_relationship($options);

if (count($wikis) >= 1) {
	
	foreach ($wikis as $wiki) {
		$wiki_link = elgg_view('output/url', array(
			'text' => $wiki->title,
			'href' => $wiki->getURL(),
			'target' => '_blank',
		));
		$wiki_content .= "<li>{$wiki_link}</li>";
	}
	
	$wiki_label = elgg_echo('googleapps:label:groupwikis');
	
	$content = <<<HTML
		<div class='odd even'>
			<strong>$wiki_label:</strong><br />
			<ul>
				$wiki_content
			</ul>
		</div>
HTML;

	echo $content;
} 

