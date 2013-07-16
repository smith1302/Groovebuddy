
$(document).ready(function() {
	var suggestionArray = {};
	var currentPlaylistSongIndex = 0;
	var currentPlaylistSong = 0;
	var currentPlaylistID = 0;
	var currentPlaylist = new Array();
	var playState = $("#main-play-state-icon");
	var musicState = -1;  // 0 = pause, 1 = play, -1 = nothing is playing
	var songFromSuggestion = 0; // 1 = keep playing next song,  0 one song then stop
	var queueingSong = 0;
	var playerStateUpdated = 1;

	window.stopMusic = function() {
		window.player.stopStream();
		//Find if there is something playing; Find parent and remove stop button replace with play
		if (songFromSuggestion) {
			var alreadyPlayingSuggestion = $(".stop-suggestion").parent("li");
			$(".stop-suggestion").remove();
			alreadyPlayingSuggestion.append("<i class='icon-play pull-right play-suggestion'></i>");
		}
	}

	function resumeMusic() {
		window.player.resumeStream();
	}

	function pauseMusic() {
		window.player.pauseStream();
	}

	//GRAB ALBUM ART
	function getAlbumArt(songName){
		return $.post("phpRequests/getAlbumArt.php", {query: songName});
	}

	//DOUBLE CLICK WHOLE PLAYLIST TO PLAY
	$(document).on("dblclick", ".real-post .status-container", function() {
		playFromPost($(this));
	});

	function playFromPost(element) {
		playerStateUpdated = 0;
		//empty old playlist
		emptyPlaylist();
		currentPlaylistID = element.attr("id");
		var promise = getPlaylist(currentPlaylistID);
		openPost(currentPlaylistID);
		hightlightPost(currentPlaylistID);
		promise.success(function() {
			currentPlaylistSongIndex = 0;
			currentPlaylistSong = currentPlaylist[currentPlaylistSongIndex];
			console.log("Done! Starting to play");
			findAndSelectSong(currentPlaylistID, currentPlaylistSong);
			playSong(currentPlaylistSong);
		})
	}

	//DOUBLE CLICK INDIVIDUAL PLAYLIST TO PLAY
	$(document).on("dblclick", ".playlist-row", function() {
		playFromPlaylistRow($(this));
	});

	function playFromPlaylistRow(element) {
		playerStateUpdated = 0;
		emptyPlaylist();
		var songID = element.attr("value");
		currentPlaylistID = element.attr("id");
		var myarr = currentPlaylistID.split("-");
		currentPlaylistID = myarr[0];
		var promise = getPlaylist(currentPlaylistID);
		promise.success(function() {
			currentPlaylistSongIndex = currentPlaylist.indexOf(songID);
			findAndSelectSong(currentPlaylistID, songID);
			playSong(songID);
		})
	}

	//ADD SONG TO OPEN MODAL
	$(document).on("click", ".add-song", function() {
		currentPlaylistSong = $(this).attr('value');
	});

	function findAndSelectSong(postPrimkey, songID) {
		var selected = $("#"+postPrimkey+"-"+songID);
		selectSongInPlaylist(selected);
	}

	function selectSongInPlaylist(selected) {
		selected.siblings().each(function() {
			if($(this).hasClass("selected") && $(this) != selected) {
				$(this).removeAttr("style")
				$(this).removeClass("selected");
			}
		});
		if(!selected.hasClass("selected") && !selected.hasClass("blank")) {
			selected.css('background-color', '#E0A314');
			selected.addClass("selected");
			console.log("changing selected song color");
		}
	}

	function emptyPlaylist() {
		currentPlaylist = [];
	}

	function getPlaylist(postPrimkey) {
		console.log("retrieving songs with id: "+postPrimkey);
		return $.post("phpRequests/getPlaylist.php",{postPrimkey: postPrimkey},
			function(e) {
				var result = jQuery.parseJSON(e);
				console.log("Songs:");
				var songID;
				for (var i = 0; i < result.length; i++) {
					songID = result[i]["songID"];
					currentPlaylist.push(songID);
					console.log("> "+songID);
				};
			});
	}

	function addToPlaylist(songID, Url, songName, artistName, album) {
		console.log("Add to playlist");
		$.post(
			"phpRequests/addToPlaylist.php",
			{songID: songID, Url: Url, songName: songName, artistName: artistName, primkey: currentPlaylistSong, album: album},
			function(e) {
				console.log("Done adding... Errors: "+e);
				stopMusic();
				reloadPlaylist(currentPlaylistSong);
			});
	}

	//ADD SUGGESTION IS CLICKED
	$(document).on("click", "#add-suggestion", function() {
		var selected = $(".suggestionSelect");
		var songID = selected.val();
		var songName = suggestionArray[songID]["SongName"];
		var artistName = suggestionArray[songID]["ArtistName"];
		var Url = suggestionArray[songID]["Url"];
		var promise = getAlbumArt(songName+" - "+artistName);
		promise.success(function (content) {
		  	var album = extractAlbumURL(content, artistName, songName, songID);

		  	if (album == "img/defaultAlbum.png") {
		  		var promise = getAlbumArt(artistName);
				promise.success(function (content) {
					var album = extractAlbumURL(content, artistName, songName, songID);
					if(!selected) {
						alert("Must select a song to add it!");
					}else{
						$('#myModal').modal('hide');
						addToPlaylist(songID, Url, songName, artistName, album);
					}
				});
		  	}else{
				if(!selected) {
					alert("Must select a song to add it!");
				}else{
					$('#myModal').modal('hide');
					addToPlaylist(songID, Url, songName, artistName, album);
				}
			}
		});
	});

	function extractAlbumURL(content, artistName, songName, songID) {
		var album = $(content).find(".image_border").attr("src");
		console.log("Add suggestion info:");
		console.log(songID+": "+songName+" - "+artistName+" , AlbumArt: "+album);
		if (!album) {
			album = "img/defaultAlbum.png";
		}
		return album;
	}

	// PLAY SUGGESTION  -  select and play suggested song sample
	$(document).on("click", ".play-suggestion", function() {
		stopMusic();
		songFromSuggestion = 1;
		//find songID and play it
		var parent = $(this).parent("li");
		selectSuggestion(parent);
		var songID = parent.val();
		playSong(songID);
		//change play to pause
		$(this).remove();
		parent.append("<i class='icon-stop pull-right stop-suggestion'></i>")
	});

	// STOP SUGGESTION  -  stop suggest song music
	$(document).on("click", ".stop-suggestion", function() {
		stopMusic();
	});

	// SUGGESTION CLICKED  -  Highlights search suggestion
	$(document).on("click", ".suggestion", function() {
		selectSuggestion($(this));
	});

	function selectSuggestion(element) {
		element.siblings("li").each(function() {
			$(this).removeClass("suggestionSelect");
		})
		element.addClass("suggestionSelect");
	}

	// SUGGESTION SEARCH BAR  -  Grabs suggested songs related to your search query
	$(document).on("click", "#song-search-btn", function() {
		var query = $("#search-box").val();
		query = query.replace(" ", "+");
		$.ajax({
            url:"api/getExtendedSongSearch.php",
            type: "POST",
            data:{
                query: query
            },
            success: function(data){
                console.log("getExtendedSongSearch response: "+data);
                data = jQuery.parseJSON(data);
                $("#suggestion-list").empty();
                for (var i = 0; i < data.length; i++) {
                	var SongID = data[i].SongID;
	                var ArtistName = data[i].ArtistName;
	                var Url = data[i].Url;
	                var SongName = data[i].SongName;
	                var tempArray = {"SongName":SongName, "ArtistName":ArtistName, "Url":Url};
	                suggestionArray[SongID] = tempArray;
                	$("#suggestion-list").append("<li class='suggestion' value='"+SongID+"'><span class='suggestionInfo pull-left'>"+SongName+" - "+ArtistName+"</span><i class='icon-play pull-right play-suggestion'></i></li>")
           		}
            }
        });
	});

	function playSong(songID) {
		if (queueingSong > 1) {
			queueingSong--;
			console.log("Uh oh... double queue, stopping current request. "+queueingSong+" in the queue now.");
			return;
		}
		if (songID) {
			changeMainPlayerState(songID);
	        currentSongID = songID;
	        mainPlayerLoading();
	        if (queueingSong > 1) {
				queueingSong--;
				return;
			}
	        $.ajax({
	          url: "api/GroovesharkAPI/songGetter.php",
	          type: "POST",
	          data: {
	            song: songID
	          },
	          success: function(response) {
	          	queueingSong = 0;
	            var responseData = jQuery.parseJSON(response);
	            console.log("Play song response: "+response);
	            if (!responseData.StreamKey) {
	            	alert("Unfortunately this song can not be played at this time");
	            }else{
	         		window.player.playStreamKey(responseData.StreamKey, responseData.StreamServerHostname, responseData.StreamServerID);
	         		if (!songFromSuggestion){
	         			window.player.setSongCompleteCallback("playNextSong");
	         			console.log("Song has finished... playing next song");
	         		}else {
	         			window.player.setSongCompleteCallback("stopMusic");
	         			console.log("suggestion song has finished... stopping");
	         		}
	       		}
	          }
        	});
        }else{
        	alert("Unfortunately that song cannot be played right now");
        }
    }

    window.playNextSong = function() {
    	playerStateUpdated = 0;
    	var playlistLength = currentPlaylist.length;
    	// length = 0 means its a random song, there is no next song in queue
    	if (playlistLength == 0) {
    		handleEmptyPlaylistQueue();
    	}else{
    		collapsePlayerInfo();
    		$(".player-info").promise().done(function() {
    			queueingSong = 0;
    			$(this).hide();
		    	nextSongState(playlistLength);
		    	console.log("New playlist index: "+currentPlaylistSongIndex);
				currentPlaylistSong = currentPlaylist[currentPlaylistSongIndex];
				console.log("New playlist songID: "+currentPlaylistSong);
				findAndSelectSong(currentPlaylistID, currentPlaylistSong);
				mainPlayerLoading();
				expandPlayerInfo();
				playSong(currentPlaylistSong);
			});
		}
	}

	function playPreviousSong() {
		playerStateUpdated = 0;
		var playlistLength = currentPlaylist.length;
    	// length = 0 means its a random song, there is no next song in queue
    	if (playlistLength == 0) {
    		handleEmptyPlaylistQueue();
    	}else{
    		collapsePlayerInfo();
    		$(".player-info").promise().done(function() {
    			queueingSong = 0;
    			$(this).hide();
		    	previousSongState(playlistLength);
		    	console.log("New playlist index: "+currentPlaylistSongIndex);
				currentPlaylistSong = currentPlaylist[currentPlaylistSongIndex];
				console.log("New playlist songID: "+currentPlaylistSong);
				findAndSelectSong(currentPlaylistID, currentPlaylistSong);
				mainPlayerLoading();
				expandPlayerInfo();
				playSong(currentPlaylistSong);
			});
		}
	}

	function handleEmptyPlaylistQueue() {
		collapsePlayerInfo();
		if (queueingSong > 1) {
				queueingSong--;
				return false;
		}
		$(".player-info").promise().done(function() {
			if (queueingSong > 1) {
				queueingSong--;
				return false;
			}else{
				$(this).hide();
	    		var promise = getRandomSongID();
				promise.success(function(songID) {
						if (queueingSong > 1) {
							queueingSong--;
							return false;
						}
						mainPlayerLoading();
						playSong(songID);
				});
			}
		});
	}

	function nextSongState(playlistLength) {
		if (currentPlaylistSongIndex == playlistLength-1) {
    		currentPlaylistSongIndex = 0;
    		console.log("reseting playlist index...");
    	}else{
    		currentPlaylistSongIndex++;
    		console.log("increasing playlist index...");
    	}
	}

	function previousSongState(playlistLength) {
		if (currentPlaylistSongIndex == 0) {
    		currentPlaylistSongIndex = playlistLength-1;
    		console.log("Going to the end of playlist...");
    	}else{
    		currentPlaylistSongIndex--;
    		console.log("decreasing playlist index...");
    	}
	}

	function executeStateChange(songName, artistName, album) {
		console.log("Change state details: ");
		console.log("> songName: "+songName);
		console.log("> artistName: "+artistName);
		console.log("> album: "+album);
		// -----------------------------------------
		if(queueingSong <= 1) {
		$("#playing-album").attr("src", album).show('slow');
		$("#playingSongName").html(songName+" - ").show('slow');
		$("#playingArtistName").html(artistName).show('slow');

			expandPlayerInfo();
			makeMainPlay();
			playerStateUpdated = 1;
		}
	}

	function expandPlayerInfo() {
		if ($(".player-info").css("visibility") == "hidden" || $(".player-info").css("display") == "none") {
			$(".player-info").css("max-width","0px");
		 	$(".player-info").show().animate({'max-width':'450px'}, 1500);
		 }
	}

	function collapsePlayerInfo() {
		stopMusic();
	 	$(".player-info").animate({'max-width':'0px'}, 1000);
	}

	function collapseThenOpenPlayerInfo() {
		makeMainNoPlay();
		stopMusic();
		//collapse
	 	$(".player-info").animate({'max-width':'0px'}, 1000, function() {
	 		$(this).hide();
	 		if ($(".player-info").css("visibility") == "hidden" || $(".player-info").css("display") == "none") {
				$(".player-info").css("max-width","0px");
			 	$(".player-info").show().animate({'max-width':'450px'}, 1500);
			 }
	 	});
	}

	function mainPlayerLoading() {
		console.log("Loading!")
		makeMainNoPlay();
		$("#playing-album").attr("src", "img/defaultAlbum.png");
		$("#playingSongName").html("Loading...");
		$("#playingArtistName").html("");
	}

	function makeMainPlay() {
		if (playState.hasClass("no-play")) {
		 	playState.removeClass("icon-play");
		 	playState.removeClass("no-play");
		 	playState.addClass("icon-pause");
		 	playState.addClass("play");
		 	musicState = 1;
		 }
	}

	function makeMainNoPlay() {
		if (!playState.hasClass("no-play")) {
		 	playState.removeClass("icon-pause");
		 	playState.removeClass("play");
		 	playState.addClass("icon-play");
		 	playState.addClass("no-play");
		 	musicState = 0;
		 }
	}

	function toggleMainPlayBtn() {
		makeMainPlay();
		makeMainNoPlay();
	}

	function changeMainPlayerState(songID) {
		if(!playerStateUpdated) {
			$.post("phpRequests/getInfoFromSongID.php", {songID: songID}, function(data) {
				var info = jQuery.parseJSON(data);
				var artistName = info[0]["artistName"];
				var songName = info[0]["songName"];
				var album = info[0]["album"];
				executeStateChange(songName, artistName, album);
			});
		}
	}

	$(document).on("click", "#toggle-song-button", function(e) {
		e.preventDefault();
		mainPlayBtnHandler();
	});

	function mainPlayBtnHandler() {
		if(musicState == 1) {
			pauseMusic();
			makeMainNoPlay();
		}else 
		if (musicState == 0) {
			resumeMusic();
			makeMainPlay();
		}else{
			mainPlayerLoading();
			if(selectedRow) {
				expandPlayerInfo();
				playFromPlaylistRow(selectedRow);
			}else if (selectedPost) {
				expandPlayerInfo();
				playFromPost(selectedPost);
			}else{ // play random song
				playerStateUpdated = 0;
				var promise = getRandomSongID();
				promise.success(function(songID) {
					playSong(songID);
				});
			}
		}
	}

	function getRandomSongID() {
		return $.post("phpRequests/getRandomSongID.php");
	}

	$(document).on("click", "#next-song-btn", function(e) {
		e.preventDefault();
		stopMusic();
		playNextSong();
		setQueue();
	});

	$(document).on("click", "#previous-song-btn", function(e) {
		e.preventDefault();
		stopMusic();
		playPreviousSong();
		setQueue();
	});

	var space = false;
	//detect if space is "clicked"
	$(document).keyup(function(evt) {
	    if (evt.keyCode == 32) {
	    	if (space && canUseSpace()) {
	    		mainPlayBtnHandler();
	    	}
	      space = false;
	    }
	  }).keydown(function(evt) {
	    if (evt.keyCode == 32) {
	     	space = true;
	    }
	});

	var right = false;
	//detect if right is "clicked"
	$(document).keyup(function(evt) {
	    if (evt.keyCode == 39) {
	    	if (right) {
	    		stopMusic();
				playNextSong();
				setQueue();
	    	}
	      right = false;
	    }
	  }).keydown(function(evt) {
	    if (evt.keyCode == 39) {
	      right = true;
	    }
	});

	 var left = false;
	//detect if left is "clicked"
	$(document).keyup(function(evt) {
	    if (evt.keyCode == 37) {
	    	if (left) {
	    		stopMusic();
				playPreviousSong();
				setQueue();
	    	}
	      left = false;
	    }
	  }).keydown(function(evt) {
	    if (evt.keyCode == 37) {
	      	left = true;
	    }
	});

	function canUseSpace() {
		if ($(document.activeElement).attr("type") == "text" || $(document.activeElement).attr("type") == "textarea") {
		  return false;
		}else{
			return true;
		}
	}

	function setQueue() {
		queueingSong++;
	}

});