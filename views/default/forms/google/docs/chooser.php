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

$user = elgg_get_logged_in_user_entity();
/*
This used to be called on every load of this form:

	googleapps_fetch_oauth_data(authorized_client(true), false, 'docs');

All that accomplished was refreshing the list of documents at load time. 
Since we have an oauth_update action running every minute (see JS lib) we already
have a recent list of documents saved in the session

*/
$google_docs = unserialize($_SESSION['oauth_google_docs']);


$docs_list = '<table class="elgg-table" width="100%">
					<thead>
						<tr>
							<th>' . elgg_echo('googleapps:label:table_select') . '</th>
							<th>' . elgg_echo('googleapps:label:table_name') . '</th>
							<th>' . elgg_echo('googleapps:label:table_collaborators') . '</th>
							<th>' . elgg_echo('googleapps:label:table_updated') . '</th>
						</tr>
					</thead>
					<tbody>';

$documents_collaborators = array();

// Filteres to clean up the type for CSS output
$filters = array(
	'application/' => '',
	'text/' => '',
);

foreach ($google_docs as $id => $doc) {

	$collaborators = $doc['collaborators'];
	$permission_str = get_permission_str($collaborators);
	
	// Apply filters
	$type = $doc['type'];
	foreach ($filters as $search => $replace) {
		$type = str_replace($search, $replace, $type);
	}

	$docs_list .= '
    <tr>
		<td><input type="radio" name="document_id" value="' . $doc['id'] . '"></td>
		<td>
			<span class="document-icon ' . $type . '"></span>
		 	<a href="' . $doc["href"] . '" target="_blank">' . $doc["title"] . '</a>
		</td>
		<td>' . $permission_str.'</td>
		<td>' . friendly_time($doc["updated"]) . '</td>
    </tr>
    ';
}

$docs_list .= '</tbody></table></div>';

// Labels for the tabs
$share_url_label = elgg_echo('googleapps:tab:share_url');
$share_browse_label = elgg_echo('googleapps:tab:share_browse');

// Inputs
$url_input = elgg_view("input/text", array('name' => 'document_url', 'value' => $url));

echo <<<HTML
	<ul class='elgg-tabs'>
		<li class='elgg-state-selected'>
			<a class='googleapps-docs-share-switch' style='cursor: pointer;' href='#googleapps-docs-share-browse'>$share_browse_label</a>
		</li>
		<li>
			<a class='googleapps-docs-share-switch' style='cursor: pointer;' href='#googleapps-docs-share-url'>$share_url_label</a>
		</li>
	</ul>

	<div id='googleapps-docs-share-browse' class='googleapps-docs-share-div'>
		$docs_list
		<br />
	</div>
	<div id='googleapps-docs-share-url' class='googleapps-docs-share-div' style='display: none;'>
		$url_input
		<br /><br />
	</div>
HTML;
?>