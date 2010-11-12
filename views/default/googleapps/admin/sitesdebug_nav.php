<?php
$page = $vars['page'];
$tabs = array(array('title'=>'All Sites','url'=>$vars['url'].'pg/googlesitesdebug','selected'=>$page[0]=="" ? true : false),
							array('title'=>'Sites by User','url'=>$vars['url'].'pg/googlesitesdebug/byuser','selected'=>$page[0]=="byuser" ? true : false),
							array('title'=>'Reset','url'=>$vars['url'].'pg/googlesitesdebug/reset','selected'=>$page[0]=="reset" ? true : false));

echo elgg_view('navigation/tabs',array('type'=>'horizontal','tabs'=>$tabs));
