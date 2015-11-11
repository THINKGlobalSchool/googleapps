<?php
/**
 * Elgg googleapps shared success view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$owner = get_entity($vars['container_guid']);

$success_class = elgg_extract('success_class', $vars);

$context = elgg_extract('context', $vars, 'share');

// Don't set forward url unless in share context (embed success handling is handled in JS)
if ($context == 'share') {
	if (elgg_instanceof($owner, 'group')) {
		$forward_url = elgg_get_site_url() . "googleapps/docs/group/{$owner->guid}/owner";
	} else {
		$forward_url = elgg_get_site_url() . 'googleapps/docs/' . $owner->username;
	}
}

$shared_label = elgg_echo('googleapps:label:documentshared');

echo <<<HTML
	<p><label>$shared_label</label></p>
	<a href='$forward_url' class="$success_class"><button class='elgg-button-submit pas mrs mtm'>Ok</button></a>
HTML;
?>