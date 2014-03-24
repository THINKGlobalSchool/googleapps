<?php
/**
 * Googleapps Plugin Settings
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

// Get existing settings
$googleapps_domain     = elgg_get_plugin_setting('googleapps_domain', 'googleapps');
$login_secret          = elgg_get_plugin_setting('login_secret', 'googleapps');
$private_key           = elgg_get_plugin_setting('private_key', 'googleapps');
$oauth_update_interval = elgg_get_plugin_setting('oauth_update_interval', 'googleapps');
$oauth_sync_email      = elgg_get_plugin_setting('oauth_sync_email', 'googleapps');
$oauth_sync_sites      = elgg_get_plugin_setting('oauth_sync_sites', 'googleapps');
$oauth_sync_docs       = elgg_get_plugin_setting('oauth_sync_docs', 'googleapps');
$oauth_admin_account   = elgg_get_plugin_setting('oauth_admin_account', 'googleapps');
$login_label           = elgg_get_plugin_setting('google_login_label', 'googleapps');
$domain_label          = elgg_get_plugin_setting('google_domain_label', 'googleapps');
$drive_api_client_id   = elgg_get_plugin_setting('google_drive_api_client_id', 'googleapps');
$drive_api_key         = elgg_get_plugin_setting('google_drive_api_key', 'googleapps');

// Set defaults for oauth sync options
if (!$oauth_sync_email) {
	$oauth_sync_email = 'yes';
}
if (!$oauth_sync_sites) {
	$oauth_sync_sites = 'yes';
}
if (!$oauth_sync_docs) {
	$oauth_sync_docs = 'yes';
}

// Default login label
if (!$login_label) {
	$login_label = elgg_echo('googleapps:label:googlelogin');
}

// Label/Inputs
$google_domain_label = elgg_echo('googleapps:admin:domain');
$google_domain_input = elgg_view('input/text', array(
	'name' => 'params[googleapps_domain]', 
	'value' => $googleapps_domain
));

$oauth_secret_label = elgg_echo('googleapps:admin:secret');
$oauth_secret_input = elgg_view('input/text', array(
	'name' => 'params[login_secret]', 
	'value' => $login_secret
));

$oauth_update_label = elgg_echo('googleapps:admin:oauth_update_interval');
$oauth_update_input = elgg_view('input/text', array(
	'name' => 'params[oauth_update_interval]',
	'value' => $oauth_update_interval
));

$admin_account_label = elgg_echo('googleapps:admin:2_legged_account');
$admin_account_input = elgg_view('input/text', array(
	'name' => 'params[oauth_admin_account]',
	'value' => $oauth_admin_account
));

$drive_client_label = elgg_echo('googleapps:admin:drive_client');
$drive_client_input = elgg_view('input/text', array(
	'name' => 'params[google_drive_api_client_id]',
	'value' => $drive_api_client_id
));

$drive_key_label = elgg_echo('googleapps:admin:drive_key');
$drive_key_input = elgg_view('input/text', array(
	'name' => 'params[google_drive_api_key]',
	'value' => $drive_api_key
));

// Reusable yes/no options
$yes_no = array(
	elgg_echo('googleapps:admin:yes') => 'yes',
	elgg_echo('googleapps:admin:no') => 'no'
);

$sync_email_label = elgg_echo('googleapps:admin:sync_email');
$sync_email_input = elgg_view('input/radio', array(
	'name' => 'params[oauth_sync_email]', 
	'options' => $yes_no, 
	'value' => $oauth_sync_email
));

$sync_sites_label = elgg_echo('googleapps:admin:sync_sites');
$sync_sites_input = elgg_view('input/radio', array(
	'name' => 'params[oauth_sync_sites]',
	'options' => $yes_no, 'value' => $oauth_sync_sites
));

$sync_docs_label = elgg_echo('googleapps:admin:sync_docs');
$sync_docs_input = elgg_view('input/radio', array(
	'name' => 'params[oauth_sync_docs]',
	'options' => $yes_no,
	'value' => $oauth_sync_docs
));

// This is temporary
$login_text_label = elgg_echo('googleapps:admin:loginlabel');
$login_text_input = elgg_view('input/text', array(
	'name' => 'params[google_login_label]',
	'value' => $login_label
));

// Friendy domain label
$domain_text_label = elgg_echo('googleapps:admin:domainlabel');
$domain_text_input = elgg_view('input/text', array(
	'name' => 'params[google_domain_label]',
	'value' => $domain_label
));

// Authentication/Authorization Module
$auth_title = elgg_echo('googleapps:admin:authentication');

$auth_body = <<<HTML
	<div>
		<label>$google_domain_label</label><br />
		$google_domain_input
	</div><br />
	<div>
		<label>$oauth_secret_label</label><br />
		$oauth_secret_input
	</div><br />
	<div>
		<label>$oauth_update_label</label><br />
		$oauth_update_input
	</div><br />
	<div>
		<label>$admin_account_label</label><br />
		$admin_account_input
	</div><br />
	<div>
		<label>$drive_client_label</label><br />
		$drive_client_input
	</div><br />
	<div>
		<label>$drive_key_label</label><br />
		$drive_key_input
	</div><br />
HTML;

$auth_module = elgg_view_module('inline', $auth_title, $auth_body);

echo $auth_module;

// General module
$general_title = elgg_echo('googleapps:admin:pluginsettings');

$general_body = <<<HTML
	<div>
		<label>$sync_email_label</label><br />
		$sync_email_input
	</div><br />
	<div>
		<label>$sync_sites_label</label><br />
		$sync_sites_input
	</div><br />
	<div>
		<label>$sync_docs_label</label><br />
		$sync_docs_input
	</div><br />
	<div>
		<label>$login_text_label</label><br />
		$login_text_input
	</div><br />
	<div>
		<label>$domain_text_label</label><br />
		$domain_text_input
	</div><br />
HTML;

$general_module = elgg_view_module('inline', $general_title, $general_body);

echo $general_module;
