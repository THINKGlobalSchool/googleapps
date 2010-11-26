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

	$user = get_loggedin_user();
	
	$client = authorized_client(true);
	googleapps_fetch_oauth_data($client, false, 'docs');
	$google_docs = unserialize($_SESSION['oauth_google_docs']);
	
	//print_r_html($google_docs[0]);

	$documents_collaborators=array();
	foreach ($google_docs as $id => $doc) {

	    $collaborators = $doc['collaborators'];
	    $permission_str = get_permission_str($collaborators);

	    $content .= '
	    <tr>
			<td><input type="radio" name="document_id" value="' . $doc['id'] . '"></td>
			<td>
				<span class="document-icon ' . $doc["type"] . '"></span>
			 	<a href="' . $doc["href"] . '">' . $doc["trunc_title"] . '</a>
			</td>
			<td>' . $permission_str.'</td>
			<td>' . friendly_time($doc["updated"]) . '</td>
	    </tr>
	    ';
	}
	$content .= '</tbody></table></div>';
	
	
	$script .= '<script type="text/javascript">		
		function sort_number (n) {
			if (n < 10) {
				return "00" + n;
			} else if (n < 100) {
				return "0" + n;
			} else {
				return n.toString();
			}
		};
		/*$(function(){
			$("#docs_table").tablesorter({
				textExtraction: function (x) {
					var n = parseInt(x.firstChild.innerHTML, 10);
					return isNaN(n) ? x.innerHTML : sort_number(n);
				}
			})
		});*/
		
		</script>';
	
	echo $content;//$script;

?>