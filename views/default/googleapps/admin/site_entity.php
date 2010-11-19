<?php
	/**
	 * Googleapps site entity display view (for admin)
	 *
	 * @package googleapps
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright FlatSourcing 2010
	 * @link http://www.thinkglobalschool.org
	 */
	
	$site = $vars['site_entity'];
	$owner_user = get_user($site->owner_guid);
?>
<ul>
	<li>Site GUID: <?= $site->guid; ?></li>
	<li>Title: <?= $site->title; ?></li>
	<li>Owner: <?= $owner_user->username." (". $owner_user->guid . ")"; ?></li>
	<li>URL: <?= "<a href='{$site->url}'>{$site->url}</a>"; ?></li>
	<li>Access Level: <?= $site->site_access_id; ?></li>
	<li>Last modified: <?= date(DATE_ATOM,$site->modified); ?>
</ul>
