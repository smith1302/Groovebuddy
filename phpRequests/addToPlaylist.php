<?php
	require '../dbconf.php';
	$songID = $_POST['songID'];
	$Url = $_POST['Url'];
	$songName = $_POST['songName'];
	$artistName = $_POST['artistName'];
	$album = $_POST['album'];
	$primkey = $_POST['primkey'];

	try {
		$time = time();
		$data = $conn->prepare('INSERT INTO Playlists 
								(songID, songName, artistName, postPrimkey, album, time) 
								VALUES (?,?,?,?,?,?)');
		$data->execute(array($songID, $songName, $artistName, $primkey, $album, $time));
		updatePlaylistCountForPosts();
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}

	/* REMINDERS

	* Append artist name and song name to hidden inputs instead of song - artist
	* Get primkey of post clicked, pass through addToPlaylist
	*/
?>

