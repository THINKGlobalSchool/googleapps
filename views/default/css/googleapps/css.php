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

/* Fix stripe in overlay */
.ui-widget-overlay { 
	background: #000000 !important;
}

/* Permissions Form */
.elgg-form-google-docs-permissions {

} 

.elgg-form-google-docs-permissions .permissions-update-input {

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

/* LOGIN BUTTON */

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

/*** GOOGLE DOCS PICKER **/
#google-doc-picker {
	width: 100%;
	margin-bottom: 10px;
}

#google-docs-selected {
	display: none;
	text-align: center;
}

#google-docs-selected-inner {
	display: inline-block;
	box-shadow: 0 0 6px #666666;
	padding: 5px;
}

#google-docs-selected-icon {
	position: relative;
    top: 2px;
}

#google-docs-selected-title {
	color: #444444;
	font-weight: bold;
	padding-left: 1px;
	vertical-align: top;
}

#google-docs-selected-modified {
	color: #666666;
	padding-left: 20px;
	vertical-align: top;
}

.picker.modal-dialog-bg {
	z-index: 9005; /* Override modal bg */
}

.picker.modal-dialog {
	z-index: 9006 !important; /* Override modal dialog */
}

/*</style>*/