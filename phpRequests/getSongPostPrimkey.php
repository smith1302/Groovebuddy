<?php
	require '../dbconf.php';
	$primkey = $_POST['primkey'];
	try {
		$data = $conn->prepare('SELECT * FROM Playlists WHERE primkey=?');
		$data->execute(array($primkey));
		$results = $data->fetch();
		$postPrimkey = $results["postPrimkey"];
		echo $postPrimkey;
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>