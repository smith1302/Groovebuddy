<?php
	require '../dbconf.php';
	$postPrimkey = $_POST['postPrimkey'];
	try {
		$data = $conn->prepare('SELECT * FROM playlists WHERE postPrimkey = ? ORDER BY likes DESC, time DESC');
		$data->execute(array($postPrimkey));
		$playlist = $data->fetchAll();
		echo json_encode($playlist);
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>