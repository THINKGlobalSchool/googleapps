<?php
/**
 * Googleapps wiki sort filter
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$order = get_input('order', 'ASC');
$order_by = get_input('by', 'alpha');

if (get_input('offset')) {
	$offset = "&offset=" . get_input('offset');
}

$order_label = elgg_echo('googleapps:label:wikisortby');

$order_input = elgg_view('input/dropdown', array(
	'id' => 'googlapps-wiki-orderby',
	'class' => 'googleapps-sort',
	'options_values' => array(
		'alpha' => elgg_echo('googleapps:label:orderalpha'),
		'updated' => elgg_echo('googleapps:label:orderupdated'),
	),
	'value' => $order_by,
));

$text = "<label>$order_label:</label>$order_input";

elgg_register_menu_item('googleapps-wiki-sort-menu', array(
	'name' => 'googleapps_wiki_order_by',
	'text' => $text,
	'href' => FALSE,
	'priority' => 300,
));

if ($order == 'ASC') {
	elgg_register_menu_item('googleapps-wiki-sort-menu', array(
		'name' => 'googleapps_wiki_order',
		'text' => elgg_echo('googleapps:label:sortdesc'),
		'href' => '?order=DESC&by=' . $order_by . $offset,
		'id' => 'googleapps-wiki-order',
		'title' => 'Sort DESC',
		'priority' => 400,
	));
} else {
	elgg_register_menu_item('googleapps-wiki-sort-menu', array(
		'name' => 'googleapps_wiki_order',
		'text' => elgg_echo('googleapps:label:sortasc'),
		'href' => '?order=ASC&by=' . $order_by . $offset,
		'id' => 'googleapps-wiki-order',
		'title' => 'Sort ASC',
		'priority' => 400,
	));
}

$filter_menu = elgg_view_menu('googleapps-wiki-sort-menu', array(
	'class' => 'elgg-menu-hz elgg-menu-googleapps-wiki-filter',
	'sort_by' => 'priority',
));

echo $js;
echo $filter_menu;
