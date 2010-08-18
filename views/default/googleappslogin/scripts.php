<?php

	$interval = $GLOBALS['oauth_update_interval'] ? $GLOBALS['oauth_update_interval'] : 3;
	$url = $GLOBALS['oauth_update_url'];
	$user = $_SESSION['user'];
	
	$oauth_sync_email = get_plugin_setting('oauth_sync_email', 'googleappslogin');
	$oauth_sync_sites = get_plugin_setting('oauth_sync_sites', 'googleappslogin');
	
	if ($url && $user && ($oauth_sync_email != 'no' || $oauth_sync_sites != 'no' || $oauth_sync_docs != 'no')) {
		
		?>
		<!--<script type="text/javascript" src="/mod/googleappslogin/views/default/googleappslogin/system_messages.js"></script>-->
		<script type="text/javascript">
			
			jQuery(function($) {
				
				var is_new_doc = false;
				
				function oauth_update() {
					//alert("<?= $url ?>".replace(/http(s?):\/\/.*?\//, '/'));
					$.getJSON("<?= $url ?>".replace(/http(s?):\/\/.*?\//, '/'), function (data) {
					<?php
					if ($oauth_sync_email != 'no') {
					?>
						//alert("Mail count: "+data.mail_count);
						if (data.mail_count == 0) {
							data.mail_count = '&nbsp;';
							$('#unreadmessagescountlink').removeClass('new');
						} else {
							$('#unreadmessagescountlink').addClass('new');
						}
						var mail_text = 'You have ' + (data.mail_count ? data.mail_count : 'no') + ' unread messages';
						$('#unreadmessagescountlink').attr('title', mail_text);
						$('#unreadmessagescountlink').html('<span>' + data.mail_count + '</span>');
					<?php
					}
					
					if ($oauth_sync_docs != 'no') {
					?>
						
						if (data.new_docs == 1) {
							var widget_id = search_widget_id($("#google_docs_widget"));
							setTimeout(function(){update_widget(widget_id, '<?=$user->username?>')}, '100');
						}
					<?
					}
					?>
					
					});
				}
				
				oauth_update();
				setInterval(oauth_update, (<?= $interval ?> * 60 * 1000));
				
			});
			
		</script>
		
		<div id="custom-messages" class="hidden">
			
			<div class="messages radius8">
				<p></p>
			</div>
			
			<div class="errors radius8">
				<p></p>
			</div>
			
		</div>
		
		<style>
		.nodecor:hover {
			text-decoration: none !important;
		}
		</style>
		
		<?
	}
?>
