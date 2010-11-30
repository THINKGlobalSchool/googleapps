<?php
/**
 * Googleapps sync settings form
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$user = $_SESSION['user'];
$user_sync_settings = unserialize( $user->sync_settings );

$enabled = array ();

if(!is_array($user_sync_settings)) {
    $user_sync_settings['sync_name'] = 1;
	$user->sync_settings = serialize($user_sync_settings);
    $user->save();
}

foreach ($user_sync_settings as $setting => $v) {
    if ($v) $enabled[]=$setting;
}


?>
<div class="contentWrapper">
	<div class="notification_methods">

	<?php if ($user->google == 1 || $subtype == 'googleapps') { ?>
			
			<p><?php echo elgg_echo('googleapps:usersettings:sync_description'); ?></p>
					<?php
					$body = "<p>" . elgg_view('input/checkboxes', array('internalname' => "sync_settings", 'value' =>$enabled,  'options' => array('Syncing name upon login'=>'sync_name')) );
					$body .= '</p>';
					$body .= elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'submit_button'));

					echo elgg_view('input/form',array(
						'body' => $body,
						'method' => 'post',
						'action' => elgg_get_site_url() . 'action/googleapps/save_user_sync_settings',
					));

					echo elgg_view('googleapps/disconnect');

	} else {
		$googleapps_screen_name = $user->googleapps_screen_name;
	?>
			<h3><?php echo elgg_echo('googleapps:usersettings:login_title'); ?></h3>
			<p><?php echo elgg_echo('googleapps:usersettings:login_description'); ?></p>
	<?php
		echo elgg_view('googleapps/connect');
	}
	?>
			
	</div>
</div>