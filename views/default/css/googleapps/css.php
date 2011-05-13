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

div#share_browse {
	padding-top: 10px;
	padding-bottom: 10px;
}

/* Gmail Icon */
span.google-email-notifier {
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
	cursor:pointer;
	margin-top: -5px;
}

table.googleapps-wiki-activity-settings {
	width: 500px;
	margin: 10px auto;
}

table.googleapps-wiki-activity-settings th {
	border: 0;
	border-bottom: 1px solid #888888;
	font-weight: bold;
	text-align: left;
}

table.googleapps-wiki-activity-settings tr {
	border-bottom: 1px solid #888888;
	padding-top: 10px;
	padding-bottom: 10px;
}

table.googleapps-wiki-activity-settings td {
	vertical-align: middle;
	padding-top: 10px;
	padding-bottom: 10px;
}

table.googleapps-wiki-activity-settings td.wiki-access {
 text-align: right;
}

table.googleapps-wiki-activity-settings tr.wiki-submit-row {
	border:0;
}

table.googleapps-wiki-activity-settings td.wiki-submit-cell {
	text-align:right;
}

#googleapps #googleapps-docs-loading {
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

#googleapps #googleapps-docs-loading img {
	display: block;
	margin-left: auto;
	margin-right: auto;
	margin-top: 7px;
	margin-bottom: 7px;
}

#googleapps #googleapps-docs-loading p {
	text-align: center;
	font-weight: bold;
	color: #333;
}

#googleapps-docs-browse-table {
	border: 1px solid #bbb;
	height: 300px;
	overflow: scroll;
	overflow-x: hidden;
	width: 100%
}

#googleapps-docs-browse-table table {
	width: 100%;
}

#googleapps-docs-browse-table table td {
	padding: 4px;
	border-right: 1px solid #aaa;
}

#googleapps-docs-browse-table table th {
	text-align: center;
	border-top: 0;
	border-left: 0;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	color: #666;
	font-weight: bold;
}

#googleapps-docs-browse-table table tr:nth-child(odd) {
	background: #ddd;
}

#googleapps-docs-browse-table table td.doc-select {
	width: 10px;
}

#googleapps-docs-browse-table table td.doc-name {
	width: 40%;
}

#googleapps-docs-browse-table table td.doc-collaborators {
	width: 25%;
}

#googleapps-docs-browse-table table td.doc-updated {
	width: 25%;
}

a.googleapps-tooltip {
	padding-left: 5px;
	font-size: 11px;
	z-index: 10;
}

a.googleapps-tooltip:hover {
	position:relative;
	z-index:100;
	text-decoration: none;
	cursor: pointer;
}
			
a.googleapps-tooltip span{
	display:none;
}

a.googleapps-tooltip:hover span {
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
