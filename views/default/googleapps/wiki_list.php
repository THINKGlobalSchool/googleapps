<?php
	/**
	 * Elgg googleapps index page
	 *
	 * @package googleapps
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Alexander Ulitin <alexander.ulitin@flatsoft.com>
	 * @copyright FlatSourcing 2010
	 * @link http://www.thinkglobalschool.org
	 */

	global $CONFIG;

	$user = get_loggedin_user();
	$sites = $vars['wikis'];
	
	$site_list = array();
	foreach ($sites as $number => $site) {
		if (isset($site_list[$site->site_id])) {
			$actual_site = $site_list[$site->site_id];
			if ($actual_site->owner_guid != $site->owner_guid) {
				if ($actual_site->other_owners == null) {
					$other_owners = array();
				} else {
					$other_owners = unserialize($actual_site->other_owners);
				}
				
				$other_owners[$site->owner_guid] = $site->owner_guid;
				$actual_site->other_owners = serialize(array_unique($other_owners));
				unset($sites[$number]);
			}
		} else {
			$site_list[$site->site_id] = $site;
		}
	}
	
	$content .= '<div id="googleapps">';
	$content .= '<div class="contentWrapper singleview">';
	
	foreach ($site_list as $number => $site) {            

		$owner = get_entity($site->owner_guid);
		$owners = array();
		$owners[] = $owner;
		
		$other_owners = array();
		if (!empty($site->other_owners)) {
			$other_owners = unserialize($site->other_owners);
			foreach ($other_owners as $owner) {
				$owners[] = get_entity($owner);
			}
		}
		$c = 0;
		$owners_string = '';
		foreach ($owners as $owner) {


			$owners_string .= '<a href="/profile/' . $owner->username . '">' . $owner->name . '</a>';
			if ($c + 1 < count($owners)) {
				$owners_string .= ', ';
			}
			$c++;
		}
		
		$content .= '
			<div class="search_listing">
				<div class="search_listing_icon">
					<div class="icon">
						<img border="0" src="' . elgg_get_site_url() . 'mod/googleapps/graphics/icon_site.jpg">
					</div>
				</div>
				<div>
					<div>
						<p><b><a href="' . $site->url . '">' . $site->title . '</a></b></p>
					</div>
		';



		if ($site->modified) {
			$content .= '
					<div>
						Updated ' . friendly_time(  $site->modified) . '
					</div>';
		}
		$content .= '
					<div>
						Owners: ' . $owners_string . '
					</div>
				</div>
			</div>
		';
	}

	$content .= '</div><div class="clearfloat"></div></div>';

	echo $content;
?>
