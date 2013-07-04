<?php
	require '../dbconf.php';
	$message = $_POST['input-status'];
	try {
		$data = $conn->prepare('INSERT INTO Posts (post) VALUES (?)');
		$data->execute(array($message));
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>