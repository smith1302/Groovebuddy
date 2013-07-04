<?php
	require '../dbconf.php';
	try {
		$data = $conn->prepare('SELECT * FROM playlists ORDER BY RAND() LIMIT 0,1');
		$data->execute();
		$playlist = $data->fetch();
		echo $playlist["songID"];
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>