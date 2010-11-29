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

#googleapps-icon {
	padding: 10px 10px 10px 10px;
	display: block;
	text-align: center;
}

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
	padding: 15px;
	margin: 15px;
	width: 100px;
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

/* TABLESORTER */
table.tablesorter {
	font-size: 12px;
	background-color: #FFF;
	border: 1px solid #000;
}
table.tablesorter th {
	text-align: left;
	padding: 5px;
	background-color: #6E6E6E;
	border-bottom: 1px solid #000;
}
table.tablesorter td {
	color: #000;
	padding: 5px;
}
table.tablesorter .even {
	background-color: #3D3D3D;
}
table.tablesorter .odd {
	background-color: #6E6E6E;
}
table.tablesorter .header {
	background-image: url(<?php echo elgg_get_site_url() . "mod/googleapps/graphics/"; ?>bg.png);
	background-repeat: no-repeat;
	border-left: 1px solid #FFF;
	border-right: 1px solid #000;
	border-top: 1px solid #FFF;
	padding-left: 30px;
	padding-top: 8px;
	height: auto;
}
table.tablesorter .headerSortUp {
	background-image: url(<?php echo elgg_get_site_url() . "mod/googleapps/graphics/"; ?>asc.png);
	background-repeat: no-repeat;
}
table.tablesorter .headerSortDown {
	background-image: url(<?php echo elgg_get_site_url() . "mod/googleapps/graphics/"; ?>desc.png);
	background-repeat: no-repeat;
}


