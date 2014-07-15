<?php
/**
 * Elgg Google Apps Login
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */
elgg_load_css('elgg.social_login');

$sslroot = elgg_get_config('sslroot');

$googleapps_url = elgg_add_action_tokens_to_url($sslroot . 'action/google/auth/login', FALSE);

$login_label = elgg_get_plugin_setting('google_login_label', 'googleapps');

if (!$login_label) {
	$login_label = elgg_echo('googleapps:label:googlelogin');
}
?>
<hr class='google-hr' />
<center>
	<div class='google-login-or'><?php echo $login_label; ?></div>
	<a class='btn-auth btn-google' href="<?php echo $googleapps_url;?>">Google Apps</a>
</center>

