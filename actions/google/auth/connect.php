<?php
/**
 * Googleapps connect action
 * - Handles connecting a google apps account to a spot account
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/Http.php");
require_once (dirname(dirname(dirname(dirname(__FILE__)))). "/lib/Google_OpenID.php");
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/secret.php");

$home_url = elgg_get_site_url();

$user = page_owner_entity();

//echo '<pre>';print_r($user->googleapps_controlled_profile);exit;
if (!$user) {    	
	$user = $_SESSION['user'];
}

$subtype = $user->getSubtype();

if (!$user->google) {	
	$user->sync = '1';
	$user->googleapps_controlled_profile = 'no';
	
	$user->save();

	$google = new Google_OpenID();
	$google->use_oauth();
	$google->set_home_url($home_url);
	$google->set_return_url(elgg_add_action_tokens_to_url($home_url . 'action/google/auth/return_with_connect', FALSE));
	if ($googleapps_domain) {
		$google->set_start_url('https://www.google.com/accounts/o8/site-xrds?ns=2&hd=' . $googleapps_domain);
	} else {
		$google->set_start_url("https://www.google.com/accounts/o8/id");
	}
	
	try {
		$url = $google->get_authorization_url();
		forward($url);
	} catch(Exception $e) {
		register_error(sprintf(elgg_echo("googleapps:error:wrongdomain"), $username));
		forward();
	}
} else {
	forward('googleapps/settings/account');
}

exit;
