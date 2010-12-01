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
<script type="text/javascript">
	setTimeout('window.location.replace(\'<?php echo $forward_url ?>\')', 3000);
</script>