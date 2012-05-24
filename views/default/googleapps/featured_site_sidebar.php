<?php
/**
 * Googleapps featured wiki sidebar
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

elgg_push_context('widgets');
$featured_sites = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'featured_wiki',
	'metadata_value' => 'yes',
	'type' => 'object',
	'subtype' => 'site',
	'limit' => 10,
));
elgg_pop_context();

if ($featured_sites) {
	elgg_push_context('widgets');
	$body = '';
	foreach ($featured_sites as $site) {
		$body .= elgg_view_entity($site, array('full_view' => false));
	}
	elgg_pop_context();

	echo elgg_view_module('aside', elgg_echo("googleapps:label:featuredsites"), $body);
}
