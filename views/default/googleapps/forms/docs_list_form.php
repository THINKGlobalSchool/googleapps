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
	
	// Get users groups/channels
	$groups = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => $user->getGUID(), 'types' => 'group', 'limit' => 9999));
	$shared_access = elgg_get_entities_from_relationship(array(	'relationship' => 'shared_access_member', 'relationship_guid' => $user->getGUID(), 'limit' => 999));

	$client = authorized_client(true);
	googleapps_fetch_oauth_data($client, false, 'docs');
	$google_docs = unserialize($_SESSION['oauth_google_docs']);

	$content .= '<form action="' . $GLOBALS['share_doc_url'] . '" method="post" onsubmit="return ajax_submit(this)" >';
	$content .= '<div class="contentWrapper singleview">';
	$content .= '<label>Comment to add</label><br /><textarea name="comment" class="docs_comment"></textarea><br /><br />';
    $content .= '<label>Tags</label><br /><input type="text" name="tags" class="docs_tags"></textarea><br /><br />';
	$content .= '<div class="docs_table">            
  		<table width="100%" id="docs_table" class="tablesorter">
		    <thead>
		      <tr><th width="70"></th><th  width="200"><b>Name</b></th><th><b>Sharing</b></th><th><b>Modified</b></th></tr>
		    </thead><tbody>';

	$documents_collaborators=array();
	foreach ($google_docs as $id => $doc) {

	    $collaborators =$doc['collaborators'];
	    $permission_str=get_permission_str($collaborators);

	    $content .= '
	    <tr>
		<td><input type="radio" name="doc_id" value="'.$id.'"></td>
		<td><span class="document-icon '.$doc["type"].'"></span>
			 <a href="' . $doc["href"] . '">' . $doc["trunc_title"] . '</a></td>
		<td>'.$permission_str.'</td>
		<td>'.friendly_time( $doc["updated"] ).'</td>
	    </tr>
	    ';
	}
	$content .= '</tbody></table></div>';
	
	$content .= '';
	$content .= '<br />View access level: <select name="access" id="access" onchange="showGroups()">';
	$content .= '<option value="public">Public</option>';
	$content .= '<option value="logged_in">Logged in users</option>';
	if (is_array($groups) || is_array($shared_access)) {
		$content.='<option value="group">Group or Shared Access</option>';
	}
	$content .= '<option value="match">Match permissions of Google doc</option>';
	$content .= '</select>';


	$group_and_channels_list = '&nbsp;<span id="group_list"><select name="group_channel">';

	foreach ($groups as $group) {
		$group_and_channels_list .= '<option value="gr'. $group->guid . '">' . $group->name . '</option>';
	}

	foreach ($shared_access as $shared) {
		$group_and_channels_list .= '<option value="ch'. $shared->guid . '">' . $shared->title . '</option>';
	}

	$group_and_channels_list .= '</select></span>';

	$content .= $group_and_channels_list;
	$content .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="Share doc"></form>';
	$content .= '</div><div class="clearfloat"></div></div>';
	
	$script .= '<script type="text/javascript">
		var group_list = document.getElementById("group_list");
		group_list.style.display="none";

		function showGroups(){    
		    var val = document.getElementById("access").value;
		    if(val=="group") {
		        group_list.style.display="";
		    } else {
		        group_list.style.display="none";
		    }

		}
		
		function sort_number (n) {
			if (n < 10) {
				return "00" + n;
			} else if (n < 100) {
				return "0" + n;
			} else {
				return n.toString();
			}
		};
		$(function(){
			$("#docs_table").tablesorter({
				textExtraction: function (x) {
					var n = parseInt(x.firstChild.innerHTML, 10);
					return isNaN(n) ? x.innerHTML : sort_number(n);
				}
			})
		});</script>';
	
	echo $content . $script;

?>