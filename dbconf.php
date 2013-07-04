<?php
	try {
		$conn = new PDO('mysql:host=127.0.0.1;dbname=groovebuddy', 'root', 'lolly', array(PDO::ATTR_PERSISTENT => true));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
?>