<?php

gatekeeper();
reset_googlesites();

system_message(elgg_echo('googleappslogin:googlesitesreset'));
forward($_SERVER['HTTP_REFERER']);

