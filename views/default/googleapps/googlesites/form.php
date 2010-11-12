<?php


// Echo title
echo elgg_view_title(elgg_echo('googleapps:google_sites_settings'));

/* not used anywhere?
$options = array(elgg_echo('googleapps:settings:yes')=>'yes',
		elgg_echo('googleapps:settings:no')=>'no'
);  */

$access_types = array(
		'private' => '0',
		'logged-in' => '1',
		'public' => '22'
);

$user = $_SESSION['user'];
$subtype = $user->getSubtype();

if ($user->connect == 1) {
	$subtype = 'googleapps';
	$user->google = 1;
}

$response=googleapps_sync_sites();
$user_site_entities=$response['site_entities'];

$_SESSION['user_site_entities']=serialize($user_site_entities);

?>
<div class="contentWrapper">
	<div class="notification_methods">
		<?php

		if ($user->google == 1 || $subtype == 'googleapps') {
			$site_list = unserialize($user->site_list);
			//var_dump($site_list); die;
			
			if (!empty($site_list)) {
				
				echo '<p>'.elgg_echo('googleapps:google_sites_settings_description').'</p>';
				
				$body = '<table class="wiki_activity_settings"><tr><th>'.elgg_echo('googleapps:site').'</th><th>'.elgg_echo('googleapps:access_level').'</th></tr>';
				foreach ($site_list as $site_id => $site_obj) {

          $title=$site_obj['title'];
          $access=$site_obj['access'];

					if (!empty($title)) {
						if (is_null($access)) {
							$access = 1;
						}

						//$body .= '<p><b>'. $title . '</b><br />' . elgg_view('input/radio',array('internalname' => "googleapps_sites_settings[" . $site_id . "]", 'options' => $access_types, 'value' => $access)) . '</p>';
						$access_input = elgg_view('input/access', array(
							'internalname' => 'googleapps_sites_settings['.$site_id.']',
							'value' => $access
						));
						$body .= '<tr><td>'.$title.'</td><td class="access_col">'.$access_input.'</td></tr>';
						
					}
				}
				$body .= '<tr class="submit_row"><td colspan=2 class="submit_cell">'.elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'submit_button')).'</td></tr>';
				$body .= '</table>';
				echo elgg_view('input/form',array(
				'body' => $body,
				'method' => 'post',
				'action' => $vars['url'] . 'action/googleapps/save',
				));
			}
		}

		?>
	</div>
</div>