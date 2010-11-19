<?php
	/**
	 * Googleapps document share container
	 * 
	 * @package Googleapps
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */
			
	// Form Container
	$content .= '<div id="googleapps">
					<div id="google_docs_loading">
						<img src="' . elgg_get_site_url() . '_graphics/ajax_loader_bw.gif" />
						<p>' . elgg_echo('googleapps:docsloading') . '</p>
					</div>
				</div>';

	$form_url = elgg_get_site_url() . 'pg/googleapps/docs/list_form';
	
	$script = <<<EOT
		<script type="text/javascript">
		
		function load_docs() {
			$("#googleapps").load("$form_url");
		}

		function ajax_submit(x) {
			var data = {};
			$($(x).serializeArray()).each(function (i, e) {
				data[e.name] = e.value;
			});
			$.post(x.action.replace(/^http(s?):\/\/.*?\//, "/"), data, function (r) {
				var dlg = $("<div></div>").html(r).dialog().find('form').submit(function () {
					dlg.parents('.ui-dialog').remove();
				});
				if (r.toUpperCase() === 'OK') {
					load_docs();
				}
			});
			return false;
		}
		$(load_docs);
		</script>
EOT;
	
	echo $content . $script;
	
?>				