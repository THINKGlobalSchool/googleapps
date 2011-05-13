<?php
/**
 * Googleapps update sharing permissions form
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alexander Ulitin <alexander.ulitin@flatsoft.com>
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

$content .= '<div>';
$content .= '<form action="'. $GLOBALS['change_doc_permissions_url'] .'" onsubmit="return ajax_submit(this);"  method="post">';
$content .= '<h2> '. elgg_echo('googleapps:label:action_required') . '</h2>';
$content .= '<p class="googleappsdialog_message">' . elgg_echo('googleapps:error:document_permissions_update') . '</p>';
$content .= '<input type="hidden" value="" name="answer">';
$content .= '<input class="permissions-update-input" type="submit" value="' . elgg_echo('googleapps:submit:grant') . '">&nbsp;';
$content .= '<input class="permissions-update-input" type="submit" value="' . elgg_echo('googleapps:submit:ignore') . '">&nbsp;';
$content .= '<input type="hidden" name="container_guid" value="' . $vars['container_guid'] . '" />';
$content .= '</form></div><div class="clearfloat"></div>';

echo $content;
