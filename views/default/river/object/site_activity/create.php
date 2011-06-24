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

$owner = $object->getContainerEntity();

$owner_link = "<a href='" . $owner->getURL . "'>" . $owner->name . "</a>";

$string = !empty($object->text) ? preg_replace("/\<div([^>]+)\>(.*?)\<\/div\>/", "$2", $object->text) : $object->text;

$string = str_replace($owner->name, '', $string);

$string = $owner_link . $string;

echo elgg_view('river/item', array(
	'summary' => $string,
	'item' => $vars['item']
));
