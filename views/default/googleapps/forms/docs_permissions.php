<?php
	/**
	 * Googleapps update sharing permissions form
	 *
	 * @package googleapps
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Alexander Ulitin <alexander.ulitin@flatsoft.com>
	 * @copyright FlatSourcing 2010
	 * @link http://elgg.org/
	 */

	$script = '
	<script type="text/javascript">
	function save_answer(el) {
		el.form.answer.value = el.value;
	}
	</script>';
	
	$content .= '<div class="contentWrapper singleview">';
	$content .= '<form action="'. $GLOBALS['change_doc_permissions_url'] .'" onsubmit="return ajax_submit(this);"  method="post">';
	$content .= '<h3>'. elgg_echo('googleapps:doc:share:wrong_permissions') .'</h3>';
	$content .= '<input type="hidden" value="" name="answer">';
	$content .= '<input type="submit" value="Grant view permisson" onclick="save_answer(this)">&nbsp;';
	$content .= '<input type="submit" value="Ignore and continue" onclick="save_answer(this)">&nbsp;';
	$content .= '<input type="submit" value="Cancel" onclick="save_answer(this)">&nbsp;';
	$content .= '</form></div><div class="clearfloat"></div>';

	echo $script . $content;
?>
