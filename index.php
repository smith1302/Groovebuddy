<!DOCTYPE html>
<?php
require 'stdlib.php';
include PATH_TO_ROOT.'visualPHPFunctions.php';
if(!isset($_SESSION['page'])) {
	$_SESSION['page'] = "popular";
}
?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<base href="eric3" target="_blank">
    <!-- Bootstrap -->
    <link href="<?php PATH_TO_ROOT; ?>css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php PATH_TO_ROOT; ?>css/bootstrap-select.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php PATH_TO_ROOT; ?>font-awesome/css/font-awesome.css">
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Fugaz+One' rel='stylesheet' type='text/css'>
	<style>
		body {
			background-color: #6B675B;
			/*background-image: url('img/noise.png');
			background-repeat: repeat;*/
			font-family: 'PT Sans', sans-serif;
			overflow-y: auto;
		}
		#body-container {
			/*background-color: #9B9589;
			border: 3px double #595347;
			margin-bottom: 100px;
			border-radius: 10px;
			padding: 0 1px;*/
			margin-bottom: 100px;
		}
		#bg-img {
			width: 100%;
			z-index: 0;
		}
		.line-bar {
			position: fixed;
			width: 100%;
			height: 1px;
			left: 0;
			background-color: #E39F05;
			border-top:13px solid #4A463A;
			border-bottom:3px solid #4A463A;
			z-index: 99;
		}
		.vertical-line {
			border-left: 1px solid #C0C0C0;
			right: 75px;
			height: 100%;
			position: absolute;
		}
		.h-line {
			width:100%;
			border-top:1px solid #7D796D;
			border-bottom:1px solid #5E5950;
			margin:20px 0 70px 0;
		}
		h1 {
			font-size: 70px;
			/*font-family: 'Fugaz One', cursive;*/
			font-family: 'Pacifico', cursive;
			font-weight: normal;
			color: #E39F05;
			/*text-shadow: 1px 2px 0 #FAE7BB;*/
			text-shadow: 1px 2px 0 #505050;
			margin: 0;
			margin-left: -40px;
		}
		h2 {
			font-family: sans-serif;
			text-indent: 60px;
			font-size: 17px;
			text-shadow:
			-2px 2px 0 #FFFFFF,
			2px 2px 0 #FFFFFF;
		}
		#header-text {
			position: absolute;
			width: 100%;
			left:0;
			top: 80px;
			text-align: center;
		}
		form {
			padding:0;
			margin:0;
		}
		#submit-well {
	/*		margin-top: 25px;*/
			margin-bottom: 15px;
			padding: 15px;
		}
		.inner-well {
			padding: 10px;
			margin:50px 0 0;
			border-radius: 5px;
			border: 1px solid #606060;
		}
		.wrapper-padding {
			padding-left: 9px;
			padding-right: 9px;
		}
		.light-gradient {
			background: #e8e5e2; /* Old browsers */
			background: -moz-linear-gradient(top,  #e8e5e2 0%, #cac8c6 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e8e5e2), color-stop(100%,#cac8c6)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  #e8e5e2 0%,#cac8c6 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  #e8e5e2 0%,#cac8c6 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  #e8e5e2 0%,#cac8c6 100%); /* IE10+ */
			background: linear-gradient(to bottom,  #e8e5e2 0%,#cac8c6 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e8e5e2', endColorstr='#cac8c6',GradientType=0 ); /* IE6-9 */

		}
		.inverted-darker-gradient {
			background: #9b9a98; /* Old browsers */
			background: -moz-linear-gradient(top,  #9b9a98 0%, #bab5b2 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9b9a98), color-stop(100%,#bab5b2)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  #9b9a98 0%,#bab5b2 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  #9b9a98 0%,#bab5b2 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  #9b9a98 0%,#bab5b2 100%); /* IE10+ */
			background: linear-gradient(to bottom,  #9b9a98 0%,#bab5b2 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9b9a98', endColorstr='#bab5b2',GradientType=0 ); /* IE6-9 */
		}
		.black-gradient {
			background-color: #4D4D4D;
			background-image: -moz-linear-gradient(top, #4D4D4D, #222222);
			background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#4D4D4D), to(#222222));
			background-image: -webkit-linear-gradient(top, #4D4D4D, #222222);
			background-image: -o-linear-gradient(top, #4D4D4D, #222222);
			background-image: linear-gradient(to bottom, #4D4D4D, #222222);
			background-repeat: repeat-x;
			border-color: #252525;
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff222222', endColorstr='#ff111111', GradientType=0);
		}
		input#input-status {
			width: 100%;
			margin-bottom: 0;
		}
		input#submit-status-btn {
			padding:4px 40px 5px 40px;
			float: right;
			border-top-left-radius: 0;
			border-bottom-left-radius: 0;
		}
		#input-container {
			overflow:hidden;
			display: block;
		}
		#playlist-well {
			padding:8px;
			margin:0;
		}
		.fornav {
			position:relative;
			margin-left:-22px;
			top:-3px;
			z-index:2;
		}
		#example-post {
			text-align:center;
			border-bottom: 1px solid #C2C2C2;
		}
		.example-post-text {
			color: #727272;
		}
		.white-text-shadow {
			text-shadow: 1px 1px 0px #F2F2F2;
		}
		#post-container-wrapper {
			padding: 0;
			margin:0;
		}
		.post-container {
			width: 100%;
			/*bottom border is located  in dropwdown-playlist-container*/
			border-bottom: 1px solid #C2C2C2;
			border-top: 1px solid #F0F0F0;
			position: relative;
		}
		.status-container {
			padding: 0 7px 0 34px;
			height: 40px;
			line-height: 40px;
			float:left;
			overflow: hidden;
			border-left:1px solid #C0C0C0;
			width: 80%;
		}

		.status-container:first-letter {
			font-size: 17px;
		}
		.orange-background {
			background-color: #F0E8DA;
		}
		.status-info-box {
			float:left;
			height: 40px;
			line-height: 40px;
			padding:0 8px;
			width: 120px;
			background-color: #D4D0C9;
			border-right: 1px solid #F0F0F0;
			border-left: 1px solid #F0F0F0;
			color: #303030;
		}
		.collapse-post-container {
			right: 0;
			background-color: #D4D0C9;
			height: 40px;
			width: 60px;
			padding: 0 7px;
			line-height: 40px;
			overflow: hidden;
			text-align: center;
			position: absolute;
			border-left: 2px solid #F0F0F0;
		}
		.status-box-triangle {
			width: 0; 
			height: 0; 
			border-top: 20px solid transparent;
			border-bottom: 20px solid transparent; 
			border-left:20px solid #D4D0C9; 
			left: 137px;
			position: absolute;
		}
		.status-box-triangle-border {
			width: 0; 
			height: 0; 
			border-top: 21px solid transparent;
			border-bottom: 21px solid transparent; 
			border-left:21px solid #C4C0B9;
			left: 138px;
			position: absolute;
		}
		.status-box-triangle-container {
			float: left;
			background-color: #D4D0C9;
		}
		.status-box-triangle-container .light {
			border-right-color: #F8F8F8;
		}
		.dropdown-playlist-container {
			width:100%;
			background-color: #EDEAE6;
			display:none;
		}
		.gray-icon {
			color: #505050;
		}
		.light-gray-icon {
			color: #707070;
		}
		ul {
			list-style-type: none;
			margin:0;
			padding: 0;
		}
		ul, li {
			display: inline;
			padding:0 5px 0 5px;
		}
		.orange-link {
			color: #303030;
			text-decoration: none;
		}
		.orange-link:hover{
			color: #BF740A;
			text-decoration: none;
		}
		.collapse-post-container a {
			text-decoration: none;
			color: #808080;
		}
		.collapse-post-container a:hover {
			color: #E08300;
		}
		#nav-group-container {
			margin-bottom: 5px;
		}
		#nav-well {
			padding: 7px;
			margin-top: 15px;
		}
		.white-text {
			color: white;
		}
		table {
			width: 100%;
			background-color: #969387;
			text-align: left;
		}
		td {
			text-shadow: 1px 1px 0px #404040;
			padding: 3px 10px 3px 10px;
			overflow: hidden;
			color: white;
		}
		td a{
			text-decoration: none;
			color: #A0A0A0;
			font-size: 12px;
		}
		td a:hover {
			text-decoration: none;
			color: #E08300;
		}
		td img {
			width: 20px;
			height: 20px;
			background-color: white;
			margin-right: 15px;
			padding: 1px;
			border:1px solid #BBB;
		}
		td.playlist-numbers {
			font-size: 12px;
		}
		tr.playlist-row:nth-child(even) {background: #757166}
		tr.playlist-row:nth-child(odd) {background: #8A877C}

		tr > th {
			font-weight: normal;
			color:#121212;
			padding-left: 10px;
			border-right:1px solid #B8B8B8;
		}
		.bottom-playlist-bar {
			width:100%;
			height:7px;
			border-bottom: 1px solid #B0B0B0
		}
		#suggestion-list li {
			overflow: hidden;
			white-space: nowrap;
			display: block;
			padding: 7px 5px;
			border-bottom: 1px solid #F2F2F2;
		}
		#suggestion-list i {
			color: #909090;
			font-size: 18 px;
			margin-right: 5px;
		}
		.suggestionSelect {
			background-color: #EDAD18;
			text-shadow: 1px 1px 0px #F2F2F2;
		}
		.suggestionInfo {
			white-space: nowrap;
			overflow: hidden;
			width: 88%;
		}
		.main-controller {
			padding-right: 15px;
		}
		.player-controller { 
			font-size: 20px;
			margin-top: 2px;
			padding: 0;
		}
		.player-controller a {
			text-decoration: none;
			color: #707070;
		}
		.player-controller a:hover {
			color: #E08300;
		}
		.player-info {
			display: none;
			color: #101010;
			margin-right: 15px;
			max-width: 450px;
			white-space:nowrap;
			overflow: hidden;
		}
		.player-info div {
			margin: 0 10px;
			max-width: 400px;
			white-space:nowrap;
			overflow: hidden;
			color: #DDDDDD;
		}
		img.headline {
			width: 21px;
			height: 21px;
			background-color: #E0E0E0;
			margin-right: 15px;
			padding: 1px;
			border:1px solid #BBB;
		}
		.unselectable {
		  -moz-user-select: none;
		  -webkit-user-select: none;
		  -ms-user-select: none;
		}
		.selectable {
		  -moz-user-select: text;
		  -webkit-user-select: text;
		  -ms-user-select: text;
		}
		.selectpicker {
			width: 110px;
		}
	</style>
	<title><?php echo SITE_NAME; ?></title>
</head>
<body>
	<div class="line-bar" style="top:0"></div>
	<div class="container">
		<div class="navbar navbar-fixed-bottom navbar-inverse">
		  <div class="navbar-inner">
			<a href="#" class="brand" style="margin-left: 0px"><?php echo SITE_NAME; ?></a>
			<ul class="nav">
				<li><a href="#">Find Music</a></li>
			</ul>
			<form class="navbar-search">
				<div class="input-append">
					<input type="text" class="search-query span2 search-bar-size" placeholder="Search&hellip;" style="width: 300px">
					<span class="fornav"><i class="icon-search"></i></span>
				</div>
			</form>
			<ul class="nav pull-right main-controller">
 				<li class="active player-info">
 						<a href="#">
							<div>
								<img src='img/defaultAlbum.png' id="playing-album" class='headline' />
								<span id = "playingSongName">
								</span>
								<span id = "playingArtistName">
								</span>
							</div>
						</a>
				</li>
				<li class="player-controller">
						<a href="#" style="padding-left: 6px;padding-right: 6px" id = "previous-song-btn">
							<i class="icon-backward"></i>
						</a>
				</li>
				<li class="player-controller">
						<a href="#" style="padding-top:8px" id = "toggle-song-button">
							<i class="icon-play no-play" style="font-size:23px;" id="main-play-state-icon"></i>
						</a>
				</li>
				<li class="player-controller">
						<a href="#" style="padding-left: 6px;padding-right: 6px" id="next-song-btn">
							<i class="icon-forward"></i>
						</a>
				</li>
			</ul>
		  </div>
		</div>
	</div>

	<header>
	<img src="img/header.png"></img>
		<div id="header-text">
			<h1><?php echo SITE_NAME; ?></ h1>
		</div>
	</header>

	<div class="container">
		<!--           ------              Submit Bar 					--------				-->
			<div class="well inner-well black-gradient" id="submit-well" style="margin-left:0;margin-right:0" >
				<form id="ask-form" method="post" action="processMessage.php">
				<input type="submit" id="submit-status-btn" class="btn btn-orange" value="Ask" />
				<span id="input-container">
					<input type="text" id="input-status" name="input-status" maxlength="95" placeholder="Ex: Going to the gym. Good pump up songs?" />
					<input type="hidden" value="<?php echo $_SESSION['page']; ?>" id="current-page" />
				</span>
				</form>
				<div style="clear:both"></div>
			</div>
	</div>

	<div class="container" id="body-container">
			
			<!--           ------              Nav bar 					--------				-->
			<div class="container" id="nav-group-container">
				<div class="inner-well black-gradient" id="nav-well">
					<div class="btn-group" data-toggle="buttons-radio">
					  <button id="popular-btn" class="btn" data-toggle="button">Popular</button>
					  <button id="new-btn" class="btn">New</button>
					  <button id="master-radio-btn" class="btn">Master Radio</button>
					</div>
					<div class="pull-right">
					  <select class="selectpicker" data-width="110px">
						<option>Day</option>
						<option>Week</option>
					  </select>
					</div>
				</div>
			</div>
			
			<!--           ------              PlayList Body 					--------				-->
			<div class="well inner-well black-gradient" id="playlist-well">
				<div id="post-container-wrapper" class="well">
					
					<div class="post-container white-text-shadow" id="example-post">
						<div class="status-info-box">
							<span class="example-post-text">Statistics</a>
						</div>
						<div class="status-box-triangle-border"></div>
						<div class="status-box-triangle"></div>
						<!--<div class="status-box-triangle-container">
							<div class="status-box-triangle light"></div>
						</div>-->
						<div class="status-container example-post-text" style="padding:0">Description</div>
						<div class="collapse-post-container example-post-text">
							Music
						</div>
						<div class="vertical-line"></div>
<!-- 						<div class="status-box-triangle-border"></div>
						<div class="status-box-triangle"></div> -->
						<div style="clear:both"></div>
					</div>
					<span id="generatePosts">
						<?php generatePosts($conn); ?>
					</span>
				</div>
			</div>
			
			
			
			<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Song Search</h3>
			  </div>
			  <div class="modal-body form-horizontal">
				<p><input type='text' id="search-box" name='song' placeholder='Search by songs, artists, or genres' style="width:80%" />
					<input type="button" class="btn btn-orange" value="Search" id="song-search-btn"/></p>
				<p id="suggestion-container">
					<ul id="suggestion-list">
						<li>No suggestions...</li>
					<ul>
				</p>
			  </div>
			  <div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-orange" id="add-suggestion">Add</button>
			  </div>
			</div>
			
			<div id="player"></div>
			<?php echo updatePlaylistCountForPosts(); ?>

	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="<?php PATH_TO_ROOT; ?>js/bootstrap.js"></script>
	<script src="<?php PATH_TO_ROOT; ?>js/bootstrap-select.min.js"></script>
	<script src="<?php PATH_TO_ROOT; ?>js/myJS.js"></script>
	<script src="<?php PATH_TO_ROOT; ?>js/songRequest.js"></script>
	<script>
			var page = $("#current-page").attr("value");
			$('#'+page+'-btn').button('toggle');
			// $(window).on('load', function () {
			// 	$('.selectpicker').selectpicker();
			// });
			$('#myModal').modal(options);
	</script>
	<script src="<?php PATH_TO_ROOT; ?>swfobject/swfobject.js"></script>
	<script>
	console.log(swfobject);
	    swfobject.embedSWF("http://grooveshark.com/APIPlayer.swf", "player", "0", "0", "9.0.0", "", {}, {allowScriptAccess: "always"}, {id:"groovesharkPlayer", name:"groovesharkPlayer"}, function(e) {
	        console.log(e);
	        var element = e.ref;

	        if (element) {
	            setTimeout(function() {
	                window.player = element;
	                window.player.setVolume(99);
	            }, 1500);
	        } else {
	            console.log("BAD... song element not found");
	        }

	    });
	</script>
</body>

</html>