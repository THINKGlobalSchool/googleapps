<?php
/**
 * Googleapps reset google sites view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$description = elgg_echo('googleapps:admin:resetwikis');

$reset_input = elgg_view('input/submit', array(
	'value' => elgg_echo('googleapps:admin:reset'),
	'class' => 'elgg-button elgg-button-submit',
));

echo "<div>$description $reset_input</div>";
