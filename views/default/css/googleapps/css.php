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

/** WIKI FILTER/SORT MENU **/

.elgg-menu-googleapps-wiki-filter {

}

.elgg-menu-googleapps-wiki-filter li label {
	margin-right: 10px;
	font-size: 90%;
	text-transform: uppercase;
}

.elgg-menu-googleapps-wiki-filter li.elgg-menu-item-googleapps-wiki-order {
	margin-right: 10px;
	font-size: 90%;
	text-transform: uppercase;
	font-weight: bold;
}

.elgg-menu-googleapps-wiki-filter li select {
	margin-right: 15px;
}

.elgg-menu-googleapps-wiki-filter li a {
	color: #666;
}

.elgg-menu-googleapps-wiki-filter li.elgg-state-selected a {
	font-weight: bold;
	color: inherit;
}

/** END OF SORT MENU **/

/** GOOGLE DOCS SHARE **/

#googledocsbrowser-previous,
#googledocsbrowser-next {
	font-weight: bold;
	font-size: 120%;
	color: #555 !important;
}

#googledocsbrowser-previous {
	float: left;
}

#googledocsbrowser-next {
	float: right;
}

#googledocsbrowser-loadmore {
	display: block;
	margin-top: 4px;
}

#google-docs-paging {
	text-align: center;
}

#google-docs-table th {
	font-weight: bold;
	color: #565656;
}

#google-docs-table-loader {
	background: #FFFFFF !important;
}

.google-docs-table-select {
	width: 35px;
}

.google-docs-table-name {
	width: 430px;
}

.google-docs-table-collaborators {
	width: 115px;
}

.google-docs-table-updated {
	width: 95px;
}

.google-docs-none {
	text-align: center;
	font-weight: bold;
	color: #333;
}

/** END GOOGLE DOCS SHARE **/

/***** LOGIN BUTTON (http://nicolasgallagher.com/lab/css3-social-signin-buttons) *****/
.btn-auth {
    position: relative;
    display: inline-block;
    height: 22px;
    padding: 0 1em;
    border: 1px solid #999;
    border-radius: 2px;
    margin: 0;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    line-height: 22px;
    white-space: nowrap;
    cursor: pointer;
    color: #222;
    background: #fff;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    /* iOS */
    -webkit-appearance: none; /* 1 */
    /* IE6/7 hacks */
    *overflow: visible;  /* 2 */
    *display: inline; /* 3 */
    *zoom: 1; /* 3 */
}

.btn-auth:hover,
.btn-auth:focus,
.btn-auth:active {
    color: #222;
    text-decoration: none;
}

.btn-auth:before {
    content: "";
    float: left;
    width: 22px;
    height: 22px;
    background: url("<?php echo elgg_get_site_url(); ?>mod/googleapps/graphics/auth-icons.png") no-repeat 99px 99px;
}

/*
 * 36px
 */

.btn-auth.large {
    height: 36px;
    line-height: 36px;
    font-size: 20px;
}

.btn-auth.large:before {
    width: 36px;
    height: 36px;
}

/*
 * Remove excess padding and border in FF3+
 */

.btn-auth::-moz-focus-inner {
    border: 0;
    padding: 0;
}

/* Google
   ========================================================================== */

.btn-google {
    border-color: #3079ed;
    color: #fff;
    background: #4787ed;
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#4d90fe), to(#4787ed));
    background-image: -webkit-linear-gradient(#4d90fe, #4787ed);
    background-image: -moz-linear-gradient(#4d90fe, #4787ed);
    background-image: -ms-linear-gradient(#4d90fe, #4787ed);
    background-image: -o-linear-gradient(#4d90fe, #4787ed);
    background-image: linear-gradient(#4d90fe, #4787ed);
}

.btn-google:hover,
.btn-google:focus,
.btn-google:active {
    color: #fff;
    background-color: #357ae8;
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#4d90fe), to(#357ae8));
    background-image: -webkit-linear-gradient(#4d90fe, #357ae8);
    background-image: -moz-linear-gradient(#4d90fe, #357ae8);
    background-image: -ms-linear-gradient(#4d90fe, #357ae8);
    background-image: -o-linear-gradient(#4d90fe, #357ae8);
    background-image: linear-gradient(#4d90fe, #357ae8);
}

.btn-google:active {
    -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
}

/*
 * Icon
 */

.btn-google:before {
    margin: 0 1em 0 -1em;
    background-position: -88px 0;
    background-color: #e6e6e6;
}

.btn-google.large:before {
    background-position: -144px -22px;
}

hr.google-hr {
    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}

div.google-login-or {
	font-weight: bold;
	font-size: 13px;
	margin-bottom: 7px;
	color: #555555;
}

/***** END LOGIN BUTTON *****/

/*</style>*/