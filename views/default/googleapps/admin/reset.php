<?php
/**
 * Googleapps reset google sites view
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */
?>
<form action="<?php echo $vars['url']; ?>action/google/wikis/reset" method="" name="googlesites_reset">
	<p>Reset all learned Google Sites: <input type="submit" class="elgg-action-button" value="Reset"></p>
	<?php echo elgg_view('input/securitytoken'); ?>
</form>