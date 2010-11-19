<?php
/**
 * Googleapps new mail view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$user = $_SESSION['user'];

$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleapps');

if (isset($_SESSION['new_google_mess']) && !empty($user) && ($oauth_sync_email != 'no')) {
	$count = $_SESSION['new_google_mess'];
	$domain = get_plugin_setting('googleapps_domain', 'googleapps');
	if ($count > 0) {
		$title = 'You have ' . $count . ' unread message' . (($count > 1) ? 's' : '');
		$class = 'emailnotifier new';
	} else {
		$title = "You don't have unread messages";
		$class = 'emailnotifier';
	}

	?>
	<a id="unreadmessagescountlink" class='<?php echo $class; ?>' href="https://mail.google.com/a/<?= $domain ?>" class="usersettings" target="_blank" title="<?= $title ?>">
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
