<?php
/**
 * Googleapps document share url/browse form
 * 
 * @package Googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$user = get_loggedin_user();
googleapps_fetch_oauth_data(authorized_client(true), false, 'docs');
$google_docs = unserialize($_SESSION['oauth_google_docs']);


$docs_list = '<div id="googleapps_browse_table">
				<table>
					<tbody>
						<tr>
							<th>' . elgg_echo('googleapps:label:table_select') . '</th>
							<th>' . elgg_echo('googleapps:label:table_name') . '</th>
							<th>' . elgg_echo('googleapps:label:table_collaborators') . '</th>
							<th>' . elgg_echo('googleapps:label:table_updated') . '</th>
						</tr>';

$documents_collaborators = array();
foreach ($google_docs as $id => $doc) {

    $collaborators = $doc['collaborators'];
    $permission_str = get_permission_str($collaborators);

    $docs_list .= '
    <tr>
		<td class="doc_select"><input type="radio" name="document_id" value="' . $doc['id'] . '"></td>
		<td class="doc_name">
			<span class="document-icon ' . $doc["type"] . '"></span>
		 	<a href="' . $doc["href"] . '" target="_blank">' . $doc["title"] . '</a>
		</td>
		<td class="doc_collaborators">' . $permission_str.'</td>
		<td class="doc_updated">' . friendly_time($doc["updated"]) . '</td>
    </tr>
    ';
}

$docs_list .= '</tbody></table></div>';

// Labels for the tabs
$share_url_label = elgg_echo('googleapps:tab:share_url');
$share_browse_label = elgg_echo('googleapps:tab:share_browse');

// Inputs
$url_input = elgg_view("input/text", array('internalname' => 'document_url', 'value' => $url));

echo <<<EOT
	<div class="elgg_horizontal_tabbed_nav margin_top">
		<ul>
			<li id='share_url' class='selected edt_tab_nav'>
				<a style='cursor: pointer;' onclick="javascript:googleShareFormSwitchTab('share_url')">$share_url_label</a>
			</li>
			<li id='share_browse' class='edt_tab_nav'>
				<a style='cursor: pointer;' onclick="javascript:googleShareFormSwitchTab('share_browse')">$share_browse_label</a>
			</li>
		</ul>
	</div>
	<div id='share_url' class='tab_content'>
		<br />
	        $url_input
		<br /><br />
	</div>
	<div id='share_browse' class='tab_content hidden'>
		$docs_list
		<br />
	</div>
	<script type='text/javascript'>
		function googleShareFormSwitchTab(tab_id)
		{
			var nav_name = "li#" + tab_id;
			var tab_name = "div#" + tab_id;
			// Hide all tabs
			$(".tab_content").hide();

			// Disable all tabs inputs
			$(".tab_content input").attr('disabled', 'disabled');

			// Remove selected from all tabs
			$(".edt_tab_nav").removeClass("selected");

			// Show selected tab
			$(tab_name).show();
			// Add selected class to selected tab
			$(nav_name).addClass("selected");
			// Enable selected tab
			$(tab_name + ' input').removeAttr('disabled');
		}
	</script>
EOT;

