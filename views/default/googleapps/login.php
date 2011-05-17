<?php
/**
 * Googleapps login dropdown view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

global $CONFIG;

$googleapps_url = elgg_add_action_tokens_to_url($CONFIG->sslroot . 'action/google/auth/login', FALSE);

?>
<div id="googleapps-icon">
	<a href="<?php echo $googleapps_url;?>"> <img
		src="<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/login_with_google_apps.gif"
		alt="googleapps" /> </a>
</div>
