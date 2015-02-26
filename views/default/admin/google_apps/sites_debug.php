<?php
/**
 * Google Sites/Wiki's Debug/Testing
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */
// Sites list tab
elgg_register_menu_item('googleapps-admin-menu', array(
	'name' => 'ga_list',
	'text' => elgg_echo('googleapps:label:listsites'),
	'href' => '#googleapps-admin-listsites',
	'priority' => 0,
	'item_class' => 'elgg-state-selected',
	'link_class' => 'googleapps-admin-menu-item',
));

// Tab to reset sites, not implemented
// elgg_register_menu_item('googleapps-admin-menu', array(
// 	'name' => 'ga_reset',
// 	'text' => elgg_echo('googleapps:label:resetsites'),
// 	'href' => '#googleapps-admin-resetsites',
// 	'priority' => 1,
// 	'class' => 'googleapps-admin-menu-item',
// ));

// Commands
elgg_register_menu_item('googleapps-admin-menu', array(
	'name' => 'ga_cron',
	'text' => elgg_echo('googleapps:label:cronsites'),
	'href' => '#googleapps-admin-cronsites',
	'priority' => 2,
	'link_class' => 'googleapps-admin-menu-item',
));


$menu = elgg_view_menu('googleapps-admin-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

$run_main_cron_input = elgg_view('input/button', array(
	'value' => elgg_echo('googleapps:label:cronsyncsites'),
	'id' => 'googleapps-run-sync-cron',
));

$run_group_cron_input = elgg_view('input/button', array(
	'value' => elgg_echo('googleapps:label:crongroupsites'),
	'id' => 'googleapps-run-group-cron',
));

$reset_activity_input = elgg_view('input/button', array(
	'value' => elgg_echo('googleapps:label:resetsiteactivity'),
	'id' => 'googleapps-reset-sites-activity',
));

$list_options = array(
	'type' => 'object', 
	'subtype' => 'site',
	'full_view' => FALSE,
	'limit' => 10,
);

elgg_push_context('sites_debug');
$sites = elgg_list_entities($list_options);
if ($sites) {
	$list_content = $sites;
} else {
	$list_content =  "<label>" . elgg_echo('googleapps:label:nosites') . "</label>";
}
elgg_pop_context('sites_debug');

$content = <<<HTML
	<div>
		$menu
	</div>
	<div id='googleapps-admin-listsites' class='googleapps-menu-container'>
		$list_content
	</div>
	<div style='display: none;' id='googleapps-admin-resetsites' class='googleapps-menu-container'>
		Reset
	</div>
	<div style='display: none;' id='googleapps-admin-cronsites' class='googleapps-menu-container'>
		$run_main_cron_input
		$run_group_cron_input
		$reset_activity_input
		<div id='googleapps-cron-output'>
		</div>
	</div>
HTML;

echo $content;