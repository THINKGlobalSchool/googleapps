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
/*<style>*/
/* Document Icons */
.document-icon {
    background: url("<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/mimetypes.gif") no-repeat 0 0;
	background-position:0 -50px;
    width:10px;
    height:10px;
    display:block;
    float:left;
    margin:4px 5px; 
}

.document, .rtf{
    background-position:0 -60px;
}

.spreadsheet, .csv {
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

.excel, .xls, .xlsx {
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

.word, .msword  {
    background-position:0 -80px;
}

.star {
    background-position:0 -10px;
}

.folder{
    background-position:0 -190px;
}

/* Gmail Icon */
span.google-email-notifier {
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/gmail.gif) no-repeat left 2px;
	cursor:pointer;
	margin-top: -6px !important;
}

/* Tooltip for share form */
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

/** jQuery Custom Dialog **/
.ui-widget-overlay
{
	position: fixed;
	top: 0px;
	left: 0px;
    background-color: #000000 !important;
    opacity: 0.5;
	-moz-opacity: 0.5; 
}

#googleapps-dialog  {
	padding: 10px;
	border: 8px solid #555555;
	background: #ffffff;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
}

.googleapps-dialog.ui-dialog .ui-dialog-buttonpane {
	position: absolute; 
	right: .3em; 
	top: 30px; 
	width: 19px; 
	margin: -10px 0 0 0; 
	padding: 1px; height: 18px; 
}

.googleapps-dialog.ui-dialog .ui-dialog-buttonpane button { 
	cursor: pointer; 
	padding: .2em .6em .3em .6em; 
	line-height: 1.4em; 
	width:auto; 
	overflow:visible; 
}

.googleapps-dialog.ui-dialog .ui-dialog-buttonpane button {
	-moz-border-radius:4px 4px 4px 4px;
	-webkit-border-radius: 5px 5px 5px 5px;
	background:none repeat scroll 0 0 #000000;
	border:1px solid #000000;
	color:#FFFFFF;
	cursor:pointer;
	font:bold 12px/100% Arial,Helvetica,sans-serif;
	height:25px;
	float: right; margin: .5em .4em .5em 0; 
	padding:2px 6px;
	width:auto;
}
/*</style>*/