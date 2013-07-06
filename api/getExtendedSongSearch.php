<?php
require '../stdlib.php';
require(PATH_TO_ROOT.'config.php');
require(PATH_TO_ROOT.'api/api.php');

$api = new api($secret_key);

$query = $_POST['query'];
$response = $api->getExtendedSongSearch($query);

echo json_encode($response);

?>