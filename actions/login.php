<?php
/**
 * Googleapps login action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

require_once (dirname(dirname(__FILE__)) . "/lib/Http.php");
require_once (dirname(dirname(__FILE__)) . "/lib/Google_OpenID.php");
require_once (dirname(dirname(__FILE__)) . "/lib/secret.php");

global $CONFIG;

$home_url = $CONFIG->wwwroot;

$google = new Google_OpenID();
$google->use_oauth();
$google->set_home_url($home_url);
$google->set_return_url(elgg_add_action_tokens_to_url($home_url . 'action/googleapps/return', FALSE));

if ($googleapps_domain) {
    $google->set_start_url('https://www.google.com/accounts/o8/site-xrds?ns=2&hd=' . $googleapps_domain);
} else {
    $google->set_start_url("https://www.google.com/accounts/o8/id");
}

try {
    $url = $google->get_authorization_url();
    forward($url);
} catch(Exception $e) {
    register_error(sprintf(elgg_echo("googleapps:wrongdomain"), $username));
    forward();
}

exit;
?>