<?php
/**
 * jquery UI css simplecache view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

$css_path = elgg_get_config('path');
$css_path = "{$css_path}mod/googleapps/vendors/jquery-ui-css/jquery-ui-1.8.16.css";

$graphics_path = elgg_get_site_url() . 'mod/googleapps/graphics/jquery-ui/';

ob_start();
include $css_path;
$contents = ob_get_clean();
$contents = str_replace('images/', $graphics_path, $contents);
echo $contents; 