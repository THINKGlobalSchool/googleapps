<?php
/**
 * Googleapps return with connect action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

#ini_set("display_errors", "1");
#ini_set("display_startup_errors", "1");
#ini_set('error_reporting', E_ALL);
#ini_set('pcre.backtrack_limit', 10000000);

require_once (dirname(dirname(__FILE__)) . "/liblib/Http.php");
require_once (dirname(dirname(__FILE__)) . "/lib/OAuth.php");
require_once (dirname(dirname(__FILE__)) . "/lib/Google_OpenID.php");

global $CONFIG;

$google = Google_OpenID::create_from_response($_REQUEST);
$google->set_home_url($googleapps_domain);

if (!$google->is_authorized()) {
	register_error(sprintf(elgg_echo('googleapps:googleappserror'), 'Not authorized'));
	forward('pg/googleapps/settings/account');
} else {
	
	if (!$user) {
		$user = $_SESSION['user'];
	}
	
	$email = $google->get_email();
	$firstname = $google->get_firstname();
	$lastname = $google->get_lastname();
	
	$entities = get_user_by_email($email);
	
	if (!empty($entities) && $entities[0]->username !== $user->username) {
		register_error(sprintf(elgg_echo('googleapps:googleappserror'), 'Sorry, but email ' . $email . ' already exists and in use by other user.'));
		forward('pg/googleapps/settings/account');
	}
	$is_sync = $user->sync == '1';
	if ($is_sync) {
		
		if (empty($email)) {
			register_error(sprintf(elgg_echo('googleapps:googleappserror'), 'No data'));
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
		$googleapps_return = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/googleapps/return', FALSE);
		forward($googleapps_return);
		
	} else {
		register_error(sprintf(elgg_echo('googleapps:googleappserror'), 'This user is not ready for synchronization.'));
		forward('pg/googleapps/settings/account');
	}
	
}

forward('pg/googleapps/settings/account');
exit;
?>
