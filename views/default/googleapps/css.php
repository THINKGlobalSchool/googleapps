<?php
/**
 * Googleapps main CSS
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */
?>


.toolbarimages .user_mini_avatar {
	width:16px;
	height:16px;
}
/*.hidden {
	display: none;
}*/
#custom-messages {
	margin:0 auto;
}


#custom-messages .messages, #custom-messages .errors {
	color:white;
	font-weight: bold;
	display:block;
	padding:3px 10px;
	z-index: 9600;
	position:fixed;
	right:20px;
	margin-top:10px;
	width:auto;
	cursor: pointer;
	opacity:0.9;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45); /* safari v3+ */
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45); /* FF v3.5+ */
}
#custom-messages .errors {
	background-color:red;
}
#custom-messages p {
	margin:0;
}
#unreadmessagescountlink{
	margin-left:4px !important;
}

.view-all {
    text-align:right;
    font-size:90%;
}
.document-icon {
    background: url("<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/mimetypes.gif") no-repeat 0 0;
	background-position:0 -170px;
    width:10px;
    height:10px;
    display:block;
    float:left;
    margin:4px 5px; 
}

.document{
    background-position:0 -60px;
}

.rtf {
    background-position:0 -60px;
}

.spreadsheet {
    background-position:0 -140px;
}
.presentation{
    background-position:0 -20px;
}
.form {
    background-position:0 -130px;
}

.pdf{
    background-position:0 -180px;
}

.audio {
    background-position:0 0;
}

.drawing {
    background-position:0 -90px;
}

.excel {
    background-position:0 -30px;
}

.csv {
    background-position:0 -30px;
}

.photo {
    background-position:0 -160px;
}

.powerpoint {
    background-position:0 -110px;
}

.video {
    background-position:0 -40px;
}

.word {
    background-position:0 -80px;
}

.star {
    background-position:0 -10px;
}

.folder{
    background-position:0 -190px;
}


.docs_table {
    height: 250px;
    overflow: auto; !important;
	border: 1px solid #666;
}

.docs_comment {
    width: 90%;
}

.docs_tags {
    width: 90%;
} 

div#share_browse {
	padding-top: 10px;
	padding-bottom: 10px;
}


/* messages/new messages icon & counter in elgg_topbar */
a.emailnotifier {
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
	padding-left:16px;
	margin:2px 15px 0 5px;
	cursor:pointer;
}
a.emailnotifier:hover {
	text-decoration: none;
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
}
a.emailnotifier.new {
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
	padding-left:18px;
	margin:2px 15px 0 5px;
	color:white;
}
a.emailnotifier.new:hover {
	text-decoration: none;
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
}
a.emailnotifier.new span {
	background-color: red;
	-webkit-border-radius: 10px; 
	-moz-border-radius: 10px; 
	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* safari v3+ */
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* FF v3.5+ */
	color:white;
	display:block;
	float:right;
	padding:0;
	position:relative;
	text-align:center;
	top:-1px;
	right:5px;
	min-width: 16px;
	height:16px;
	font-size:10px;
	font-weight:bold;
}


table.wiki_activity_settings {
	width: 500px;
	margin: 10px auto;
}

table.wiki_activity_settings th {
	border: 0;
	border-bottom: 1px solid #888888;
	font-weight: bold;
	text-align: left;
}

table.wiki_activity_settings tr {
	border-bottom: 1px solid #888888;
	padding-top: 10px;
	padding-bottom: 10px;
}

table.wiki_activity_settings td {
	vertical-align: middle;
	padding-top: 10px;
	padding-bottom: 10px;
}

table.wiki_activity_settings td.access_col {
 text-align: right;
}

table.wiki_activity_settings tr.submit_row {
	border:0;
}

table.wiki_activity_settings td.submit_cell {
	text-align:right;
}

#googleapps #google_docs_loading {
	border: 1px solid #666;
	background: #fff;
	padding-top: 10px;
	padding-bottom: 10px;
	margin-top: 15px;
	margin-bottom: 15px;
	width: 100%;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
}

#googleapps #google_docs_loading img {
	display: block;
	margin-left: auto;
	margin-right: auto;
	margin-top: 7px;
	margin-bottom: 7px;
}

#googleapps #google_docs_loading p {
	text-align: center;
	font-weight: bold;
	color: #333;
}

#google_docs_header a.action_button {
	display: none;
}

#googleapps_browse_table {
	border: 1px solid #bbb;
	height: 300px;
	overflow: scroll;
	overflow-x: hidden;
	width: 100%
}

#googleapps_browse_table table {
	width: 100%;
}

#googleapps_browse_table table td {
	padding: 4px;
	border-right: 1px solid #aaa;
}

#googleapps_browse_table table th {
	text-align: center;
	border-top: 0;
	border-left: 0;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	color: #666;
	font-weight: bold;
}

#googleapps_browse_table table tr:nth-child(odd) {
	background: #ddd;
}

td.doc_select {
	width: 10px;
}

td.doc_name {
	width: 40%;
}

td.doc_collaborators {
	width: 25%;
}

td.doc_updated {
	width: 25%;
}

a.gapps_tooltip {
	padding-left: 5px;
	font-size: 11px;
	z-index: 10;
}

a.gapps_tooltip:hover {
	position:relative;
	z-index:100;
	text-decoration: none;
	cursor: pointer;
}
			
a.gapps_tooltip span{
	display:none;
}

a.gapps_tooltip:hover span {
	display: block;
	position: absolute;
	float: left;
	top: -2.2em;
	left: .5em;
	background: #eeeeee;
	padding: 4px;
	border: 1px solid #444;
	color: #333;
	padding: 1px 5px;
	z-index: 10;
	text-decoration: none;	
	height: auto;
	width: 200px;		
}

/* SUPER CUSTOM DIALOG */

#googleappsdialog  {
	padding: 10px;
	border: 8px solid #555555;
	background: #ffffff;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
}

p.googleappsdialog_message {
	margin-top: 5px;
	margin-bottom: 5px;
	font-weight: bold;
	color: #333333;
}
