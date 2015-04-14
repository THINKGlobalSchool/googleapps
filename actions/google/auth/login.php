<?php
/**
 * Google login
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

$state = md5(rand());

$_SESSION['google_login_state'] = $state;

$client = googleapps_get_client();
$client->setState($state);

$authUrl = $client->createAuthUrl();

forward($authUrl);