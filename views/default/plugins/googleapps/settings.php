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

// Get plugin settings
$googleapps_domain     = elgg_get_plugin_setting('googleapps_domain', 'googleapps');

$enable_google_sites   = elgg_get_plugin_setting('enable_google_sites', 'googleapps');
$enable_google_docs    = elgg_get_plugin_setting('enable_google_docs', 'googleapps');

$google_admin_username = elgg_get_plugin_setting('google_admin_username', 'googleapps');

$login_label           = elgg_get_plugin_setting('google_login_label', 'googleapps');
$domain_label          = elgg_get_plugin_setting('google_domain_label', 'googleapps');
$google_api_client_id  = elgg_get_plugin_setting('google_api_client_id', 'googleapps');
$google_api_client_secret  = elgg_get_plugin_setting('google_api_client_secret', 'googleapps');
$google_drive_api_key  = elgg_get_plugin_setting('google_drive_api_key', 'googleapps');

// Set defaults for oauth sync options
if (!$enable_google_sites) {
	$enable_google_sites = 'yes';
}
if (!$enable_google_docs) {
	$enable_google_docs = 'yes';
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

$admin_account_label = elgg_echo('googleapps:admin:admin_username');
$admin_account_input = elgg_view('input/text', array(
	'name' => 'params[google_admin_username]',
	'value' => $google_admin_username
));

$google_api_client_label = elgg_echo('googleapps:admin:api_client_id');
$google_api_client_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_id]',
	'value' => $google_api_client_id
));

$google_api_client_secret_label = elgg_echo('googleapps:admin:api_client_secret');
$google_api_client_secret_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_secret]',
	'value' => $google_api_client_secret
));

$google_drive_api_key_label = elgg_echo('googleapps:admin:drive_api_key');
$google_drive_api_key_input = elgg_view('input/text', array(
	'name' => 'params[google_drive_api_key]',
	'value' => $google_drive_api_key
));

// Reusable yes/no options
$yes_no = array(
	elgg_echo('googleapps:admin:yes') => 'yes',
	elgg_echo('googleapps:admin:no') => 'no'
);

$enable_google_sites_label = elgg_echo('googleapps:admin:enable_google_sites');
$enable_google_sites_input = elgg_view('input/radio', array(
	'name' => 'params[enable_google_sites]',
	'options' => $yes_no, 'value' => $enable_google_sites
));

$enable_google_docs_label = elgg_echo('googleapps:admin:enable_google_docs');
$enable_google_docs_input = elgg_view('input/radio', array(
	'name' => 'params[enable_google_docs]',
	'options' => $yes_no,
	'value' => $enable_google_docs
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
		<label>$admin_account_label</label><br />
		$admin_account_input
	</div><br />
	<div>
		<label>$google_api_client_label</label><br />
		$google_api_client_input
	</div><br />
	<div>
		<label>$google_api_client_secret_label</label><br />
		$google_api_client_secret_input
	</div><br />
	<div>
		<label>$google_drive_api_key_label</label><br />
		$google_drive_api_key_input
	</div><br />
HTML;

$auth_module = elgg_view_module('inline', $auth_title, $auth_body);

echo $auth_module;

// General module
$general_title = elgg_echo('googleapps:admin:pluginsettings');

$general_body = <<<HTML
	<div>
		<label>$enable_google_sites_label</label><br />
		$enable_google_sites_input
	</div><br />
	<div>
		<label>$enable_google_docs_label</label><br />
		$enable_google_docs_input
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
