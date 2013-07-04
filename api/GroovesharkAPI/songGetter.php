<?php
    require('../../config.php');
	require("gsAPI.php");
	session_start();
	$gsapi = new gsAPI($api_key, $api_secret);
	if (isset($_SESSION['gsSession'])) {
		$gsapi->setSession($_SESSION['gsSession']);
	} else {
		$_SESSION['gsSession'] = $gsapi->startSession();
	}
	
	if (isset($_SESSION['gsCountry']) && !empty($_SESSION['gsCountry'])) {
        $gsapi->setCountry($_SESSION['gsCountry']);
    } else {
        $_SESSION['gsCountry'] = $gsapi->getCountry();
    }
    if (isset($_POST['song']) && is_numeric($_POST['song'])) {
        $songID = $_POST['song'];
    } else {
        die("noSongID");
    }

    if (!$_SESSION['gsSession']) {
        die("noSession");
    }
    if (!$_SESSION['gsCountry']) {
        die("noCountry");
    }
	
    $streamInfo = $gsapi->getStreamKeyStreamServer($songID, false);
    echo json_encode($streamInfo);
    
?>