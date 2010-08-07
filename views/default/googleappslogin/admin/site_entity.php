<?
	$site = $vars['site'];
	$owner_user = get_user($site->owner_guid);
?>
<ul>
	<li>Site GUID: <? echo $site->guid; ?></li>
	<li>Title: <? echo $site->title; ?></li>
	<li>Owner: <? echo $owner_user->username." (". $owner_user->guid . ")"; ?></li>
	<li>URL: <? echo "<a href='{$site->url}'>{$site->url}</a>"; ?></li>
	<li>Access Level: <? echo $site->site_access_id; ?></li>
	<li>Last modified: <? echo $site->last_modified; ?>
</ul>