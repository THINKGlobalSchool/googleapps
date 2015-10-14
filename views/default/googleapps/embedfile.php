<?php
/**
 * Google docs generate embed file view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$attrs = array(
	'src' => $vars['embed_link'],
	'height' => $vars['height'],
	'width' => $vars['width'],
	'frameborder' => "0"
);

$format_attrs = elgg_format_attributes($attrs);

echo "<iframe {$format_attrs}></iframe>";