<?php
	$site = $vars['site_entity'];
	$owner_user = get_user($site->owner_guid);
?>
<ul>
	<li>Site GUID: <?= $site->guid; ?></li>
	<li>Title: <?= $site->title; ?></li>
	<li>Owner: <?= $owner_user->username." (". $owner_user->guid . ")"; ?></li>
	<li>URL: <?= "<a href='{$site->url}'>{$site->url}</a>"; ?></li>
	<li>Access Level: <?= $site->site_access_id; ?></li>
	<li>Last modified: <?= $site->last_modified; ?>
</ul>
