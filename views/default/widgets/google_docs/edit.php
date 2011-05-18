<?php
/**
 * Googleapps docs widget edit view
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

if (empty($vars['entity']->max_display)) {
	$vars['entity']->max_display = 4;
}

$max_display_input = elgg_view('input/dropdown', array(
	'name' => "params[max_display]",
	'value' => $vars['entity']->max_display,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
));

/*
// Old widget
$google_folder_input = elgg_view('input/dropdown', array(
	'name' => "params[google_folder]",
	'id' => 'google_folders',
	'value' => elgg_echo('googleapps:label:allfolders'),
	'options' => array(elgg_echo('googleapps:label:allfolders')),
));
*/

?>
<div>
	<?php echo elgg_echo('googleapps:label:documentsdisplay'); ?>
	<?php echo $max_display_input; ?>
</div>

