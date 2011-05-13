<?php
/**
 * Googleapps login action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$home_url = elgg_get_site_url();

$google = new GoogleOpenID();
$google->use_oauth();
$google->set_home_url($home_url);
$google->set_return_url(elgg_add_action_tokens_to_url($home_url . 'action/google/auth/return', FALSE));

$googleapps_domain = elgg_get_plugin_setting('googleapps_domain', 'googleapps');

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

exit;