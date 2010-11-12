<form action="<?php echo $vars['url']; ?>action/googleapps/sites_reset" method="" name="googlesites_reset">
	<p>Reset all learned Google Sites: <input type="submit" class="action_button" value="Reset"></p>
	<?php echo elgg_view('input/securitytoken'); ?>
</form>