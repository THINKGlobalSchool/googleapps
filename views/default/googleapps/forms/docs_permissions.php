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

	$script = '
	<script type="text/javascript">
	function save_answer(el) {
		el.form.answer.value = el.value;
	}
	</script>';
	
	$content .= '<div>';
	$content .= '<form action="'. $GLOBALS['change_doc_permissions_url'] .'" onsubmit="return ajax_submit(this);"  method="post">';
	$content .= '<h2> '. elgg_echo('googleapps:label:action_required') . '</h2>';
	$content .= '<p class="googleappsdialog_message">' . elgg_echo('googleapps:error:document_permissions_update') . '</p>';
	$content .= '<input type="hidden" value="" name="answer">';
	$content .= '<input type="submit" value="' . elgg_echo('googleapps:submit:grant') . '" onclick="save_answer(this)">&nbsp;';
	$content .= '<input type="submit" value="' . elgg_echo('googleapps:submit:ignore') . '" onclick="save_answer(this)">&nbsp;';
	$content .= '<input type="submit" value="' . elgg_echo('googleapps:submit:cancel') . '" onclick="save_answer(this)">&nbsp;';
	$content .= '<input type="hidden" name="container_guid" value="' . $vars['container_guid'] . '" />';
	$content .= '</form></div><div class="clearfloat"></div>';

	echo $script . $content;
?>
