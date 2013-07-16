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
	<link rel="stylesheet" href="<?php PATH_TO_ROOT; ?>font-awesome/css/font-awesome.css">
	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Fugaz+One' rel='stylesheet' type='text/css'>
	<!-- JQuery UI -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<!-- Main -->
	<link href="<?php PATH_TO_ROOT; ?>css/main.css" rel="stylesheet">

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
					<input type="text" class="search-query span2 search-bar-size" placeholder="Search&hellip;" style="width: 300px" id="search-bar">
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
				<li class="player-controller">
						<a href="#" style="padding-left: 20px;padding-right: 10px" id="volume-btn">
							<i class="icon-volume-up"></i>
						</a>
				</li>
			</ul>
		  </div>
		</div>
	</div>

	<div id="volume-background">
		<div id="slider-container">
			<div id="slider"></div>
		</div>
	</div>

	<header>
	<img src="img/header.png"></img>
		<div id="header-text">
			<h1><?php echo SITE_NAME; ?></h1>
			<h2><i>Powered by Grooveshark</i></h2>
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
				<div id = "load-more-btn">
					Load More
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
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="<?php PATH_TO_ROOT; ?>js/bootstrap.js"></script>
	<script src="<?php PATH_TO_ROOT; ?>js/myJS.js"></script>
	<script src="<?php PATH_TO_ROOT; ?>js/songRequest.js"></script>
	<script>
			var page = $("#current-page").attr("value");
			$('#'+page+'-btn').button('toggle');
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