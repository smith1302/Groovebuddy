<?php

/**
 * @author Karen Zhu
 * @copyright 2011
 */
class gsCustom extends gsAPI {

    private $parent;
    private $playlistid;
    private $url;
    private $name;
    private $songs;
    private $tsmodified;
    private $user;

	function gsCustom(&$parent=null){
	   if (!$parent) {
	       $this->parent = gsAPI::getInstance();
       } else {
            $this->parent = $parent;
       }
	}

	//Grab relevant song from genre tag
    public static function startAutoplayTag($tag){
		if (!is_numeric($tag)){
			return false;
		}

		$return = parent::apiCall('startAutoplayTag',array('tagID'=>$tag));
		if (isset($return['decoded']['result'])) {
			return $return['decoded']['result'];
		} else {
			return false;
        }
	}

	//grab genre list
	public static function getAutoplayTags(){
		$return = parent::apiCall('getAutoplayTags');
		if (isset($return['decoded']['result'])) {
			return $return['decoded']['result'];
		} else {
			return false;
        }
	}

	//not working...
	public static function getAutoPlaySong($state){
		$return = parent::apiCall('getAutoplaySong',array('autoplayState'=>$state));
		if (isset($return['decoded']['result'])) {
			return $return['decoded']['result'];
		} else {
			return false;
        }
	}

	//not used
	// public static function markSongComplete($songID, $streamKey, $streamServerID, $autoplayState){
	// 	$return = parent::apiCall('markSongComplete',array('songID'=>$songID,'streamKey'=>$streamKey,'streamServerID'=>$streamServerID,'autoplayState'=>$autoplayState));
	// 	if (isset($return['decoded']['result'])) {
	// 		return $return['decoded']['result'];
	// 	} else {
	// 		return false;
 //        }
	// }


}

?>