<?php
/**
 * Googleapps create shared doc river item
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
    $user = get_loggedin_user();

	$time = !empty($object) ? strtotime($object->updated) : 0;
	$date = $time ? date('d M Y', $time) : '';
	
	$string = $object->title;
	$string .= "   " . $object->access_id;
	
?>

<?php echo $string; ?>
