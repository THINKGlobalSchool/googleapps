<?php
$user = $_SESSION['user'];

$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleappslogin');

if (isset($_SESSION['new_google_mess']) && !empty($user) && ($oauth_sync_email != 'no')) {
	$count = $_SESSION['new_google_mess'];
	$domain = get_plugin_setting('googleapps_domain', 'googleappslogin');
	if ($count > 0) {
		$title = 'You have ' . $count . ' unread message' . (($count > 1) ? 's' : '');
		$class = 'emailnotifier new';
	} else {
		$title = "You don't have unread messages";
		$class = 'emailnotifier';
	}

	?>
	<a id="unreadmessagescountlink" class='emailnotifier new' href="https://mail.google.com/a/<?= $domain ?>" class="usersettings" target="_blank" title="<?= $title ?>">
	<span>
	<?php 
		if ($count > 0) {
			echo $count;
		} else {
			echo "&nbsp;";
		}
	?>
	</span>
	</a>
<?php
}
?>
