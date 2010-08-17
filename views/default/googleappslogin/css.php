#googleappslogin-icon {
	padding: 10px 10px 10px 10px;
	display: block;
	text-align: center;
}

.toolbarimages .user_mini_avatar {
	width:16px;
	height:16px;
}
.hidden {
	display: none;
}
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
    background: url("/mod/googleappslogin/views/default/widgets/google_docs/images/mimetypes.gif") no-repeat 0 0;
    width:10px;
    height:10px;
    display:block;
    float:left;
    margin:4px 5px; 
}

.document{
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
    width: 100%;
    height: 250px;
    overflow: auto; !important;
}

.docs_comment {
    width: 90%;
}

.docs_tags {
    width: 90%;
} 

/* messages/new messages icon & counter in elgg_topbar */
a.emailnotifier {
	background:transparent url(<?php echo $vars['url']; ?>mod/googleappslogin/graphics/gmail.gif) no-repeat left 2px;
	padding-left:16px;
	margin:2px 15px 0 5px;
	cursor:pointer;
}
a.emailnotifier:hover {
	text-decoration: none;
	background:transparent url(<?php echo $vars['url']; ?>mod/googleappslogin/graphics/gmail.gif) no-repeat left 2px;
}
a.emailnotifier.new {
	background:transparent url(<?php echo $vars['url']; ?>mod/googleappslogin/graphics/gmail.gif) no-repeat left 2px;
	padding-left:18px;
	margin:2px 15px 0 5px;
	color:white;
}
a.emailnotifier.new:hover {
	text-decoration: none;
	background:transparent url(<?php echo $vars['url']; ?>mod/googleappslogin/graphics/gmail.gif) no-repeat left 2px;
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
