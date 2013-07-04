var selectedPost;
var selectedRow;

function togglePostOpen(postPrimkey) {
	var dropdown = $("#post"+postPrimkey).siblings(".dropdown-playlist-container");
	if(dropdown.is(":hidden")) {
		openCollapseBtn(postPrimkey);
		dropdown.slideDown('slow', function() {
			dropdown.show();
		});
	}else{
		closeCollapseBtn(postPrimkey);
		dropdown.slideUp('slow', function() {
			dropdown.hide();
		});
	}
}

function openPost(postPrimkey) {
	var dropdown = $("#post"+postPrimkey).siblings(".dropdown-playlist-container");
	if(dropdown.is(":hidden")) {
		openCollapseBtn(postPrimkey);
		dropdown.slideDown('slow', function() {
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

$(document).ready(function() {

		/*
		*	Toggling nav buttons to change popular to new is in bootstrap.js
		*   Button.prototype.toggle = function () {}
		*/

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

		//SELECT ROW in playlist
		$(document).on("click",".playlist-row",function() {
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
				btn.children(":first").html(data);
				reloadPosts();		
			});
		});
	});