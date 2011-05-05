<?php
/**
 * Googleapps create site/wiki river item
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);


	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	
	$string = !empty($object->text) ? preg_replace("/\<div([^>]+)\>(.*?)\<\/div\>/", "$2", $object->text) : $object->text;
	
	// Make the owner name a link, like the rest of Elgg (this is nasty..)
	$string = str_replace($performed_by->name, $url, substr($string, 0, strpos($string, '<'))) . substr($string, strpos($string, '<'));
	
	$string .= " <span class='entity_subtext'>" . friendly_time($object->time_created) . "</span>";
?>

<?php echo $string; ?>
