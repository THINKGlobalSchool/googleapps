<?php
/**
 * Elgg googleapps shared success view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

$forward_url = elgg_get_site_url() . 'pg/googleapps/docs/' . get_loggedin_user()->username;

?>

<h2>Success!</h2><p class="googleappsdialog_message">Document shared</p>
<input name='Ok' type='submit' value='Ok' id='ok_close' onclick="javascript:window.location.replace('<?php echo $forward_url ?>');" /> 
<style>
	/* Hide the close button here */
	button.ui-corner-all {
		display: none;
	}
	
	#ok_close {
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
</style>
