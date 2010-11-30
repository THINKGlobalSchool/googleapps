<?php
/**
 * Googleapps reset wikis/sites action
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

gatekeeper();
reset_googlesites();

system_message(elgg_echo('googleapps:success:sites_reset'));
forward($_SERVER['HTTP_REFERER']);

