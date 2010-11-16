<?php
$tabs = array(array('title'=>'All Sites','url'=>$vars['url'].'pg/googleapps/settings/debug','selected' => $vars['page'] == "" ? true : false),
							array('title'=>'Sites by User','url' => $vars['url'] . 'pg/googleapps/settings/debug/byuser', 'selected' => $vars['page'] == "byuser" ? true : false),
							array('title'=>'Reset','url' => $vars['url'] . 'pg/googleapps/settings/debug/reset', 'selected'=> $vars['page'] == "reset" ? true : false));

echo elgg_view('navigation/tabs',array('type'=>'horizontal','tabs'=>$tabs));
