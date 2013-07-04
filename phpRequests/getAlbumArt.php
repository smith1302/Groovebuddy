<?php
	$query = $_POST['query'];
	$query = str_replace(" ", "+", $query);
	echo file_get_contents("http://www.albumart.org/index.php?skey=".$query."&itempage=1&newsearch=1&searchindex=Music");
?>