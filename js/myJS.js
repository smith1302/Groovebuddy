var selectedPost;
var selectedRow;

function togglePostOpen(postPrimkey) {
	var dropdown = $("#post"+postPrimkey).siblings(".dropdown-playlist-container");
	if(dropdown.is(":hidden")) {
		openCollapseBtn(postPrimkey);
		dropdown.slideDown('medium', function() {
			dropdown.show();
		});
	}else{
		closeCollapseBtn(postPrimkey);
		dropdown.slideUp('medium', function() {
			dropdown.hide();
		});
	}
}

function openPost(postPrimkey) {
	var dropdown = $("#post"+postPrimkey).siblings(".dropdown-playlist-container");
	if(dropdown.is(":hidden")) {
		openCollapseBtn(postPrimkey);
		dropdown.slideDown('medium', function() {
			dropdown.show();
		});
	}
}

function hightlightPost(postPrimkey) {
			$(".real-post .status-container").each(function() {
				$(this).removeClass("orange-background");
			})
			$("#"+postPrimkey).addClass("orange-background");
			selectedPost = $("#"+postPrimkey);
}

function reloadPosts() {
	$("#generatePosts").load("phpRequests/reloadPosts.php");	
}

function reloadPlaylist(postPrimkey) {
		console.log("RELOADING: playlist"+postPrimkey);
		//$("#playlist"+postPrimkey).html("NOOOO");
		$("#playlist"+postPrimkey).load("phpRequests/reloadPlaylist.php?postPrimkey="+postPrimkey);	
}

function openCollapseBtn(postPrimkey) {
	var ele = $("#collapse"+postPrimkey);
	if (ele.hasClass("icon-collapse")) {
		ele.removeClass("icon-collapse");
		ele.addClass("icon-collapse-top");
	}
}

function closeCollapseBtn(postPrimkey) {
	var ele = $("#collapse"+postPrimkey);
	if (!ele.hasClass("icon-collapse")) {
		ele.removeClass("icon-collapse-top");
		ele.addClass("icon-collapse");
	}
}

function getSongPostPrimkey(primkey) {
	return $.post("phpRequests/getSongPostPrimkey.php", {primkey: primkey});
}

var searchBarInterval;
$(document).ready(function() {

		$( "#slider" ).slider({
	      orientation: "vertical",
	      range: "min",
	      min: 0,
	      max: 100,
	      value: 60,
	      slide: function( event, ui ) {
	      	window.player.setVolume(ui.value);
	      }
		});

		/*
		*	Toggling nav buttons to change popular to new is in bootstrap.js
		*   Button.prototype.toggle = function () {}
		*/

		$(document).on("keyup", "#search-bar", function(e) {
			clearTimeout(searchBarInterval);
			searchBarInterval = setTimeout(function() {
				console.log("Text val: "+$("#search-bar").val());
				clearTimeout(searchBarInterval);
			}, 300);
		});

		function doSearch() {
			console.log("Text val: "+$("#search-bar").val());
			clearTimeout(searchBarInterval);
		}

		//CLICK VOLUME BTN
		$(document).on("click","#volume-btn",function(e) {
			e.preventDefault();
			var ele = $("#volume-background");
			if (ele.is(":hidden")) {
				ele.slideDown("medium");
			} else {
				ele.slideUp("medium");
			}
		});

		//CLICK LIKE BTN for each song
		$(document).on("click",".like-song-btn",function(e) {
			e.preventDefault();
			var data = $(this).attr("data");
			var primkey = data.replace("song","");
			var promise = getSongPostPrimkey(primkey);
			var likeText = $(this).find(".song-likes");
			var newLikeValue = Number(likeText.text())+1;
			promise.done(function(postPrimkey){
				$.post("phpRequests/processSongLike.php", {primkey: primkey}, function() {
					likeText.fadeOut('fast', function() {
						$(this).html(newLikeValue).fadeIn('fast', function() {
							reloadPlaylist(postPrimkey);
						});
					});
				});
			});
		});


		//CLICK STATUS turn orange
		$(document).on("click",".real-post .status-container",function(e) {
			var postPrimkey = $(this).attr("id");
			hightlightPost(postPrimkey);
		});

		//CLICK DROPDOWN reveal playlist
		$(document).on("click",".real-post .collapse-post-container",function(e) {
			e.preventDefault();
			var postPrimkey = $(this).siblings(".status-container").attr("id");
			togglePostOpen(postPrimkey);
		});

		//CLICK PLAYLIST COUNT reveal playlist
		$(document).on("click",".real-post .playlist-count", function(e) {
			e.preventDefault();
			var statContainer = $(this).parent().parent().siblings(".status-container");
			var postPrimkey = statContainer.attr("id");
			var collapseIcon = statContainer.siblings(".collapse-post-container").find(".collapse-post-icon");
			if (collapseIcon.hasClass("icon-collapse")) {
				collapseIcon.removeClass("icon-collapse");
				collapseIcon.addClass("icon-collapse-top");
			}
			openPost(postPrimkey);
		});

		//like song button is a child of the row. We dont want to highlight when we like it
		$(document).on('click', ".like-song-btn", function(event){
		  event.stopPropagation();
		}); 

		//SELECT ROW in playlist
		$(document).on("click",".playlist-row", function(e) {
			var selected = $(this);
			selectSongInPlaylist(selected);
		});

		function selectSongInPlaylist(selected) {
			$(".selected").each(function() {
				if($(this) != selected) {
					$(this).removeAttr("style")
					$(this).removeClass("selected");
				}
			});
			if(!selected.hasClass("selected") && !selected.hasClass("blank")) {
				selected.css('background-color', '#E0A314');
				selected.addClass("selected");
				selectedRow = selected;
			}
		}

		// //CLICK ON STATUS PLAY BTN
		// $(document).on("click", ".main-play-btn", function(e) {
		// 	e.preventDefault();
		// 	var pbp = $(this).parent().parent().parent();
		// 	if(!pbp.hasClass("expanded")) {
		// 		expandPlay(pbp,$(this));
		// 	}else{
		// 		collapsePlay(pbp, $(this));
		// 	}
		// });

		// function expandPlay(pbp,pb) {
		// 		$(".collapse-post-container").each(function() {
		// 			if($(this).hasClass("expanded")) {
		// 				collapsePlay($(this), $(this).children("#player-controls").children(".play-list").children(".main-play-btn"));
		// 			}
		// 		});
		// 		pbp.addClass("expanded");
		// 		pbp.children("#player-controls").append("<li class='appended-li'><img src='img/Capture.png' class='headline' />Headlines - Drake</li>");
		// 		pbp.siblings(".status-container").animate({width:"-=300px"},1000);
		// 		pbp.animate({width:"+=300px"},1000);
		// 		pb.html('<i class="icon-pause icon-large"></i>');
		// }

		// function collapsePlay(pbp, pb) {
		// 	pbp.removeClass("expanded");
		// 	pbp.siblings(".status-container").animate({width:"+=300px"},1000, function() {
		// 		pbp.children("#player-controls").children(".appended-li").remove();
		// 	});
		// 	pbp.animate({width:"-=300px"},1000);
		// 	pb.html('<i class="icon-play icon-large"></i>');
		// }
		
		//SUBMIT STATUS
		$(document).on("click","#submit-status-btn",function(e) {
			e.preventDefault();
			if (!$(this).data("isClicked")) {
				$(this).data("isClicked", true);
				setTimeout(function() {
        			link.removeData('isClicked')
       			}, 5000);
				var message = $("#input-status").val();
				if (message) {
					if (message.length < 10) {
						alert("Your post must be more than 10 characters!");
					}else{
						$.post("phpRequests/processMessage.php", $("#ask-form").serialize(),function() {
							reloadPosts();
							$('#input-status').val('');
						});
					}
				}else{
					alert("Can't be blank!");
				}
			}
		});
		
		$(document).on("click",".like-btn",function(e) {
			e.preventDefault();
			var primkey = $(this).attr('value');
			var btn = $(this);
			console.log("click");
			$.post("phpRequests/processLike.php", {primkey: primkey},function(data) {
				btn.children(":first").fadeOut('fast',function() {
					$(this).html(data).fadeIn('fast', function() {
						reloadPosts();
					});
				});		
			});
		});
	});