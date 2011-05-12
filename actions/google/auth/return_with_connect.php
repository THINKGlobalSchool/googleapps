<?php
/**
 * Googleapps return with connect action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/Http.php");
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/OAuth.php");
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/Google_OpenID.php");

global $CONFIG;

$google = Google_OpenID::create_from_response($_REQUEST);
$google->set_home_url($googleapps_domain);

if (!$google->is_authorized()) {
	register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), elgg_echo('googleapps:error:notauthorized')));
	forward('googleapps/settings/account');
} else {
	
	if (!$user) {
		$user = $_SESSION['user'];
	}
	
	$email = $google->get_email();
	$firstname = $google->get_firstname();
	$lastname = $google->get_lastname();
	
	$entities = get_user_by_email($email);
	
	if (!empty($entities) && $entities[0]->username !== $user->username) {
		register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), sprintf(elgg_echo('googleapps:error:emailexists'), $email)));
		forward('googleapps/settings/account');
	}
	
	$is_sync = $user->sync == '1';
	
	if ($is_sync) {
		
		if (empty($email)) {
			register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), elgg_echo('googleapps:error:nodata')));
			forward();
		}
		
		$user->email = $email;
		if (!empty($firstname) || !empty($lastname)) {
			$user->name = $firstname . (!empty($firstname) ? ' ' : '' ) . $lastname;
		}
		$user->subtype = 'googleapps';
		$user->google = 1;
		$user->connect = 1;
		$user->googleapps_controlled_profile = 'yes';
		$user->save();
		
		$_SESSION['oauth_connect'] = 1;
		$googleapps_return = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/return', FALSE);
		forward($googleapps_return);
		
	} else {
		register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), elgg_echo('googleapps:usernotready')));
		forward('googleapps/settings/account');
	}
}

forward('googleapps/settings/account');
exit;
