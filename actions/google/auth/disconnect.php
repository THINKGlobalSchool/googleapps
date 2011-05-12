<?php
/**
 * Googleapps disconnect action
 * - Handles disconnecting a google apps account from a spot account
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$home_url = elgg_get_site_url();

$user = page_owner_entity();

if (!$user) {    	
	$user = $_SESSION['user'];
}
$subtype = $user->getSubtype();

if ($user->google) {
	
	if (empty($user->password)) {
		register_error(sprintf(elgg_echo('googleapps:error:googlereturned'), elgg_echo('googleapps:error:passworddisconnect')));
		forward(REFERER);
	}
	
	$user->sync = '0';
	$user->subtype = '';
	$user->connect = 0;
	$user->googleapps_controlled_profile = 'no';
	$user->google = 0;
	$user->access_token = '';
	$user->token_secret = '';
	$user->save();
	
	unset($_SESSION['access_token']);
	unset($_SESSION['access_secret']);
	unset($_SESSION['logged_with_openid']);
	unset($_SESSION['oauth_connect']);
	
	system_message(elgg_echo('googleapps:success:disconnect'));
}

forward('googleapps/settings/account');

exit;
