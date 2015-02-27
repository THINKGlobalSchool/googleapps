<?php
/**
 * Elgg Google Apps Google calendar object view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$cal = elgg_extract('entity', $vars);
$access = get_write_access_array();
$access_level = $access[$cal->access_id];

//build delete link
$delete_url = "action/google/calendars/delete?guid={$cal->guid}";
$delete_link = elgg_view('output/url', array(
	'href' => $delete_url,
	'text' => elgg_echo('delete'),
	'is_action' => TRUE
));

//build edit link
$edit_url = "admin/google_apps/calendars?guid=$cal->guid";
$edit_url = elgg_normalize_url($edit_url);
$edit_link = "<a rel=\"toggle\" href=\"#elgg-google-calendars-edit-$cal->guid\">" . elgg_echo('edit') . '</a>';

?>
<li class="pam mam google-calendar-feed google-calendar-feed-<?php echo $cal->guid; ?>">
	<a href="<?php echo $cal->google_cal_feed ?>">
	<?php echo $cal->title ?></a> - <?php echo $access_level; ?>
	<span class="right"><?php echo $edit_link; ?> | <?php echo $delete_link; ?></span>
	<div class="hidden" id="elgg-google-calendars-edit-<?php echo $cal->guid; ?>">
		<?php
		$vars = google_calendars_prepare_form_vars($cal);
		echo elgg_view_form('google/calendars/save', array('class' => 'mtl'), $vars);
		?>
	</div>
</li>