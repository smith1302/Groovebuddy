<?php
	require '../stdlib.php';
	include PATH_TO_ROOT.'visualPHPFunctions.php';
	$primkey = $_GET['postPrimkey'];
	generatePlaylist($primkey,$conn);
?>