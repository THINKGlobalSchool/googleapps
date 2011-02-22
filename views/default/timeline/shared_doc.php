<?php
/**
 * Timeline view for shared_doc
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

echo "<div class='entity_subtext timeline-entity-subtext'>
	</div>". elgg_get_excerpt(elgg_view('output/longtext', array('value' => $vars['entity']->description))) .
	 "<br /><br /><a href='" . $vars['entity']->getURL() . "'><i>" . elgg_echo('googleapps:label:viewdocument') . "</i></a>";
