<?php
/**
 * Googleapps google connect form
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 *
 */

echo "<div><label>" . elgg_echo('googleapps:usersettings:login_description') . "</label></div>";

echo elgg_view('input/submit', array(
	'value' => elgg_echo('googleapps:label:connect'),
	'class' => 'elgg-button elgg-button-submit',
));

echo elgg_view('input/hidden', array(
	'name' => 'google_connect_account',
	'value' => 1,
));