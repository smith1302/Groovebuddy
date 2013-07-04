<?php
function doesImageExist($url) {
	if ($url != "img/defaultAlbum.png") {
		//if ($url && getimagesize($url) !== false) {
		if ($url) {
	   		return true;
		}else{
			return false;
		}
	}else{
		return true;
	}
}

function updatePlaylistCountForPosts() {
		include 'dbconf.php';
		$data = $conn->prepare('SELECT * FROM Posts');
		$data->execute();
		$allData = $data->fetchAll(PDO::FETCH_ASSOC);
		foreach ($allData as $row) {
			$postPrimkey = $row['primkey'];
			$data = $conn->prepare('SELECT count(*) FROM Playlists WHERE postPrimkey = ?');
			$data->execute(array($postPrimkey));
			$num_rows = $data->fetchColumn();

			$data = $conn->prepare('UPDATE Posts set songs = ? WHERE primkey = ?');
			$data->execute(array($num_rows, $postPrimkey));
		}
}

?>