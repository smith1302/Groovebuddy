<?php

class api {
	private static $key;

	function api($key = null) {
		if (!empty($key)) {
			self::$key = $key;
		}else{
			trigger_error("Key is required", E_USER_ERROR);
		}
	}

	function getExtendedSongSearch($query) {
		$result = file_get_contents("http://tinysong.com/s/".$query."?format=json&limit=15&key=".self::$key."");
		return json_decode($result, true);
	}
}

?>