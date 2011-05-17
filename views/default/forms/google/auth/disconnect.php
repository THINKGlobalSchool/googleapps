<?php
/**
 * Googleapps google disconnect form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$connect_input = elgg_view('input/submit', array(
	'value' => elgg_echo('googleapps:label:disconnect'),
	'class' => 'elgg-button elgg-button-submit',
));

echo "<hr />" . $connect_input;
