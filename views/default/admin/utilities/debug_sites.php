<?php

$selected = get_input('tab', NULL);

$tabs = array(
array(
		'title' => 'All Sites',
		'url'=> 'admin/utilities/debug_sites',
		'selected' => $selected === NULL ? true : false
),
array(
		'title' => 'Sites by User', 
		'url' => 'admin/utilities/debug_sites?tab=byuser', 
		'selected' => $selected == "byuser" ? true : false
),
array(
		'title' => 'Reset',
		'url' => 'admin/utilities/debug_sites?tab=reset', 
		'selected'=> $selected == "reset" ? true : false
));

echo elgg_view('navigation/tabs', array(
	'type'=>'horizontal', 
	'tabs'=> $tabs
));


switch ($selected) {
	case "byuser" :
		$content .= googleapps_list_sites_by_user();
		break;
	case "reset":
		$content .= elgg_view_form('google/wikis/reset');
		break;
	default:
		$content .= googleapps_list_sites();
		break;
}

echo $content;