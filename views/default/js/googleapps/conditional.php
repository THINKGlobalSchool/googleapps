<?php
/**
 * Googleapps conditional JS library
 *
 * Note: Put any JS here that needs to run if, and only if, the user has a google account connected
 *
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */
?>
//<script>
elgg.register_hook_handler('init', 'ckeditor', elgg.google.addDriveButton);