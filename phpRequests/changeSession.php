<?php
	session_start();
	$session = $_POST['session'];
	if ($session == "popular-btn") {
		$_SESSION['page'] = "popular";
	}else{
		$_SESSION['page'] = "new";
	}
	echo $_SESSION['page'];
?>