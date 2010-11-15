<?php
$page = $vars['page'];
$tabs = array(array('title'=>'All Sites','url'=>$vars['url'].'pg/googleapps/settings/debug','selected'=>$page[2]=="" ? true : false),
							array('title'=>'Sites by User','url'=>$vars['url'].'pg/googleapps/settings/debug/byuser','selected'=>$page[2]=="byuser" ? true : false),
							array('title'=>'Reset','url'=>$vars['url'].'pg/googleapps/settings/debug/reset','selected'=>$page[2]=="reset" ? true : false));

echo elgg_view('navigation/tabs',array('type'=>'horizontal','tabs'=>$tabs));
