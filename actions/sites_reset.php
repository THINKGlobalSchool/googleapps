<?php

gatekeeper();
reset_googlesites();

system_message(elgg_echo('googleapps:googlesitesreset'));
forward($_SERVER['HTTP_REFERER']);

