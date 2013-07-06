<?php
	require '../dbconf.php';
	$primkey = $_POST['primkey'];
	try {
		$data = $conn->prepare('SELECT * FROM Playlists WHERE primkey=?');
		$data->execute(array($primkey));
		$results = $data->fetch();
		$likeCount = $results["likes"]+1;
		echo $likeCount;
		$data = $conn->prepare('UPDATE Playlists SET likes = ? WHERE primkey=?');
		$data->execute(array($likeCount,$primkey));
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>