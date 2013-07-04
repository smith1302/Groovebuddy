<?php
	require '../dbconf.php';
	$songID = $_POST['songID'];
	try {
		$data = $conn->prepare('SELECT * FROM playlists WHERE songID = ? LIMIT 1');
		$data->execute(array($songID));
		$playlist = $data->fetchAll();
		echo json_encode($playlist);
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>