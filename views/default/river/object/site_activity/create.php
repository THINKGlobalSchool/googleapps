<?php
/**
 * Googleapps create site/wiki river item
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$object = $vars['item']->getObjectEntity();

if ($object->category_term) {
	$action = googleapps_get_river_verb_from_category_label($object->category_label);

	$owner = $object->getOwnerEntity();

	// If the owner is an actual user, display owner link
	if (!elgg_instanceof($owner, 'group')) {
		$owner_text = "<a href='" . $owner->getURL . "'>" . $owner->name . "</a>";	
	} else {
		$owner_text = $object->author_name;
	}
	
	$summary_link = $object->summary_link;
	
	$site_text = "<a target='_blank' href='" . $object->site_url . "'>" . $object->site_name . "</a>";

	$string = elgg_echo('river:create:object:site_activity_custom', array(
		$owner_text, $action, $summary_link, $site_text
	));
	
	echo elgg_view('river/elements/layout', array(
		'summary' => $string,
		'item' => $vars['item']
	));
} else { 
	// Support for old site_activity entries
	$owner = $object->getContainerEntity();

	$owner_link = "<a href='" . $owner->getURL . "'>" . $owner->name . "</a>";

	$string = !empty($object->text) ? preg_replace("/\<div([^>]+)\>(.*?)\<\/div\>/", "$2", $object->text) : $object->text;

	$string = str_replace($owner->name, '', $string);

	$string = $owner_link . $string;

	echo elgg_view('river/elements/layout', array(
		'summary' => $string,
		'item' => $vars['item']
	));
}

