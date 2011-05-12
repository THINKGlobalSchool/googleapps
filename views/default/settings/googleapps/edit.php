<?php
/**
 * Googleapps admin settings
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */
 
$body = '';
 
$options = array(elgg_echo('googleapps:admin:yes') => 'yes',
                 elgg_echo('googleapps:admin:no') => 'no'
);
 
$googleapps_domain = get_plugin_setting('googleapps_domain', 'googleapps');
$login_key = get_plugin_setting('login_key', 'googleapps');
$login_secret = get_plugin_setting('login_secret', 'googleapps');
$private_key = get_plugin_setting('private_key', 'googleapps');
$oauth_update_interval = get_plugin_setting('oauth_update_interval', 'googleapps');
 
$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleapps');
$oauth_sync_sites = get_plugin_setting('oauth_sync_sites', 'googleapps');
$oauth_sync_docs = get_plugin_setting('oauth_sync_docs', 'googleapps');
 
$body .= elgg_echo('googleapps:admin:details');
$body .= '<br /><br />';
 
$body .= '<p><label>' . elgg_echo('googleapps:admin:domain') . "</label><br />";
$body .= elgg_view('input/text', array('internalname' => 'params[googleapps_domain]', 'value' => $googleapps_domain)) . "</p>";
 
$body .= '<p><label>' . elgg_echo('googleapps:admin:secret') . "</label><br />";
$body .= elgg_view('input/text', array('internalname' => 'params[login_secret]', 'value' => $login_secret)) . "</p>";
 
$body .= '<p><label>' . elgg_echo('googleapps:admin:oauth_update_interval') . "</label><br />";
$body .= elgg_view('input/text', array('internalname' => 'params[oauth_update_interval]', 'value' => $oauth_update_interval)) . "</p>";
 

if (!$oauth_sync_email) {
	$oauth_sync_email = 'yes';
}
if (!$oauth_sync_sites) {
	$oauth_sync_sites = 'yes';
}
if (!$oauth_sync_docs) {
	$oauth_sync_docs = 'yes';
}

$body .= '<p><label>' . elgg_echo('googleapps:admin:sync_email') . "</label><br />";
$body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_email]', 'options' => $options, 'value' => $oauth_sync_email)) . "</p>";
 
$body .= '<p><label>' . elgg_echo('googleapps:admin:sync_sites') . "</label><br />";
$body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_sites]', 'options' => $options, 'value' => $oauth_sync_sites)) . "</p>";

$body .= '<p><label>' . elgg_echo('googleapps:admin:sync_docs') . "</label><br />";
$body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_docs]', 'options' => $options, 'value' => $oauth_sync_docs)) . "</p>";
 

echo $body;
