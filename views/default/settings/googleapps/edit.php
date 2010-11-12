<?php
 
$body = '';
 
$options = array(elgg_echo('googleapps:settings:yes') => 'yes',
                 elgg_echo('googleapps:settings:no') => 'no'
);
 
$googleapps_domain = get_plugin_setting('googleapps_domain', 'googleapps');
$login_key = get_plugin_setting('login_key', 'googleapps');
$login_secret = get_plugin_setting('login_secret', 'googleapps');
$private_key = get_plugin_setting('private_key', 'googleapps');
$oauth_update_interval = get_plugin_setting('oauth_update_interval', 'googleapps');
 
$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleapps');
$oauth_sync_sites = get_plugin_setting('oauth_sync_sites', 'googleapps');
$oauth_sync_docs = get_plugin_setting('oauth_sync_docs', 'googleapps');
 
$body .= "<p><b>" . elgg_echo('googleapps:title') . "</b></p>";
$body .= '<br />';
$body .= elgg_echo('googleapps:details');
$body .= '<br />';
 
$body .= elgg_echo('googleapps:domain') . "<br />";
$body .= elgg_view('input/text', array('internalname' => 'params[googleapps_domain]', 'value' => $googleapps_domain));
 
$body .= elgg_echo('googleapps:secret') . "<br />";
$body .= elgg_view('input/text', array('internalname' => 'params[login_secret]', 'value' => $login_secret));
 
$body .= elgg_echo('googleapps:oauth_update_interval') . "<br />";
$body .= elgg_view('input/text', array('internalname' => 'params[oauth_update_interval]', 'value' => $oauth_update_interval));
 
//$logged_user = $_SESSION['user'];
 
//if ($logged_user->admin == 1) {
     
    if (!$oauth_sync_email) {
        $oauth_sync_email = 'yes';
    }
    if (!$oauth_sync_sites) {
        $oauth_sync_sites = 'yes';
    }
    if (!$oauth_sync_docs) {
        $oauth_sync_docs = 'yes';
    }
 
    $body .= elgg_echo('googleapps:googleapps_user_settings_sync_email') . "<br />";
    $body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_email]', 'options' => $options, 'value' => $oauth_sync_email));
     
    $body .= elgg_echo('googleapps:googleapps_user_settings_sync_sites') . "<br />";
    $body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_sites]', 'options' => $options, 'value' => $oauth_sync_sites));
 
    $body .= elgg_echo('googleapps:googleapps_user_settings_sync_docs') . "<br />";
    $body .= elgg_view('input/radio', array('internalname' => 'params[oauth_sync_docs]', 'options' => $options, 'value' => $oauth_sync_docs));
     
//}
 
//$body .= elgg_echo('googleapps:privatekey') . "<br />";
//$body .= elgg_view('input/longtext',array('internalname'=>'params[private_key]','value'=>$private_key));
 
echo $body;
 
?>