<?php

$googleapps_domain = 'http://' . get_plugin_setting('googleapps_domain', 'googleapps');
$login_secret = get_plugin_setting('login_secret', 'googleapps');
$private_key = get_plugin_setting('private_key', 'googleapps');

$googleapps_domain = preg_replace('/^http(s?)\:\/\//', '', $googleapps_domain);

$login_key = $googleapps_domain;

/*
$consumer_key = $googleapps_domain;
$consumer_secret = $login_secret;
*/