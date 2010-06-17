<?php

	/**
	 * Elgg googleappslogin index page
	 *
	 * @package GoogleAppsLogin
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Alexander Ulitin <alexander.ulitin@flatsoft.com>
	 * @copyright FlatSourcing 2010
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
	global $CONFIG;
	require_once($CONFIG->path . "engine/start.php");
        
	$user = $_SESSION['user'];
	$area2 = elgg_view_title(elgg_echo('googleappslogin:google_docs'));

	// Get a list of google sites
	$area2 .= '<div id="googleappslogin">Loading...</div>';
	$area2 .= '<script>
	function load_docs() {
		$("#googleappslogin").load("?action=documents");
	};
	$(load_docs);
	</script>';

        $area2.='<br />View access level: <select name="access" id="access" onchange="showGroups()">';
        $area2.='<option value="public">Public</option>';
        $area2.='<option value="logged_in">Logged in users</option>';
        $area2.='<option value="group">Group or Shared Access</option>';
        $area2.='<option value="match">Match permissions of Google doc</option>';
        $area2.='</select>';

        $groups = get_entities_from_relationship('member', $user->guid, false, 'group', '', 0,  null, false,  false);
        $group_list='&nbsp;<span id="group_list"><select name="group">';
        foreach ($groups as $group) {
            $group_list.='<option value="'.$group->guid.'">'.$group->name.'</option>';
        }
        $group_list.='</select></span>';



        $area2.=$group_list;
        $area2.='&nbsp;&nbsp;&nbsp;<input type="submit" value="Share doc"></form>';
        $area2.='</div><div class="clearfloat"></div></div>';

	$body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2, $area3);

	switch (get_input('action')) {
	default:
		// Display them in the page
		$body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2, $area3);
		// Display page
		page_draw( elgg_echo('googleappslogin:google_docs'), $body);
	break;
	case 'documents':
		$client = authorized_client(true);
		googleapps_fetch_oauth_data($client, false, 'docs');
		$google_docs = unserialize($_SESSION['oauth_google_docs']);


		$area = '';
		$area .= '<link rel="stylesheet" href="/mod/googleappslogin/css/style.css" type="text/css" /> ';
		$area .= '<script type="text/javascript" src="/mod/googleappslogin/jquery.tablesorter.js"></script> ';
		$area .= '<script>
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
		$area .= '<div class="contentWrapper singleview">';


		$area .= '<form action="'.$GLOBALS['share_doc_url'].'" method="post">';
		$area .= '<label>Comment to add</label><br /><textarea name="comment" class="docs_comment"></textarea><br /><br />';
		$area .= '<div class="docs_table">            
		  <table width="100%" id="docs_table" class="tablesorter">
		    <thead>
		      <tr><th width="70"></th><th  width="200"><b>Name</b></th><th><b>Sharing</b></th><th><b>Modified</b></th></tr>
		    </thead><tbody>';


		$documents_collaborators=array();
		foreach ($google_docs as $id => $doc) {

		    $collaborators =$doc['collaborators'];
		    $permission_str=get_permission_str($collaborators);

		    $area .= '
		    <tr>
			<td><input type="radio" name="doc_id" value="'.$id.'"></td>
			<td><span class="document-icon '.$doc["type"].'"></span>
				 <a href="' . $doc["href"] . '">' . $doc["trunc_title"] . '</a></td>
			<td>'.$permission_str.'</td>
			<td>'.friendly_time( $doc["updated"] ).'</td>
		    </tr>
		    ';
		}
		echo $area;
	exit;
	}

?>

<script type="text/javascript">
var group_list = document.getElementById('group_list');
group_list.style.display='none';

function showGroups(){    
    var val = document.getElementById('access').value;
    if(val=="group") {
        group_list.style.display='';
    } else {
        group_list.style.display='none';
    }

}
</script>  
