<?php
/**
 * Elgg googleapps shared success view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

$owner = get_entity($vars['container_guid']);

if (elgg_instanceof($owner, 'group')) {
	$forward_url = elgg_get_site_url() . "googleapps/docs/group/{$owner->guid}/owner";
} else {
	$forward_url = elgg_get_site_url() . 'googleapps/docs/' . $owner->username;
}

$header = elgg_echo('googleapps:success');

echo <<<HTML
	<h2>$header</h3>
	<p><label>Document shared</label></p>
	<a href='$forward_url'><span class='elgg-button elgg-button-action'>Ok</span></a>
	<style>
		button.ui-corner-all {
			display: none;
		}
	</style>
HTML;
?>