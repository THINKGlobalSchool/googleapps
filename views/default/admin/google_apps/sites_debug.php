<?php
/**
 * Google Sites/Wiki's Debug/Testing
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */
?>
<!-- Because UGH.. -->
<style type='text/css'>
.elgg-menu-filter {
	margin-bottom: 5px;
	border-bottom: 2px solid #ccc;
	display: table;
	width: 100%;
}
.elgg-menu-filter > li {
	float: left;
	border: 2px solid #ccc;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 10px;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}
.elgg-menu-filter > li:hover {
	background: #dedede;
}
.elgg-menu-filter > li > a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0;
	text-align: center;
	height: 21px;
	color: #999;
}
.elgg-menu-filter > li > a:hover {
	background: #dedede;
	color: #4690D6;
}
.elgg-menu-filter > .elgg-state-selected {
	border-color: #ccc;
	background: white;
}
.elgg-menu-filter > .elgg-state-selected > a {
	position: relative;
	top: 2px;
	background: white;
}
</style>
<?php

// Sites list tab
elgg_register_menu_item('googleapps-admin-menu', array(
	'name' => 'ga_list',
	'text' => elgg_echo('googleapps:label:listsites'),
	'href' => '#googleapps-admin-listsites',
	'priority' => 0,
	'item_class' => 'elgg-state-selected',
	'class' => 'googleapps-admin-menu-item',
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
	'class' => 'googleapps-admin-menu-item',
));


$menu = elgg_view_menu('googleapps-admin-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

$run_cron_input = elgg_view('input/button', array(
	'value' => elgg_echo('googleapps:label:cronsites'),
	'id' => 'googleapps-run-cron',
));

$list_options = array(
	'type' => 'object', 
	'subtype' => 'site',
	'full_view' => FALSE,
	'limit' => 10,
);

$list_content = elgg_list_entities($list_options);

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
		$run_cron_input
		<div id='googleapps-cron-output'>
		</div>
	</div>
HTML;

echo $content;