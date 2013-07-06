<?php
include 'phpFunctions.php';
session_start();

function generatePosts($conn) {
	try {
		if($_SESSION['page'] == "popular") {
			$data = $conn->prepare('SELECT * FROM Posts ORDER BY likes DESC');
		}else{
			$data = $conn->prepare('SELECT * FROM Posts ORDER BY primkey DESC');
		}
		$data->execute();
		$allData = $data->fetchAll(PDO::FETCH_ASSOC);
		$count = 0;
		foreach ($allData as $row) {
			generateIndividualPosts($row['likes'], $row['songs'], $row['post'], $row['primkey'], $conn, $count);
			$count++;
		}
		$conn = null;
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
}

function generatePlaylist($postPrimkey,$conn) {
?>
	<table class="inverted-darker-gradient">
		<colgroup>
		<col style="width: 54px">
		<col style="width: 84px">
		<col>
		</colgroup>
		<tr class="white-text-shadow light-gradient">
			<th>#</th><th>Likes</th><th>Song/Artist</th>
		</tr>

	<?php
		try {
			$data = $conn->prepare('SELECT * FROM Playlists WHERE postPrimkey = ? ORDER BY likes DESC, time DESC');
			$data->execute(array($postPrimkey));
			$rowCount = $data->rowCount();
			generateBlankSong($postPrimkey);
			if ($rowCount > 0) {
				$rows = $data->fetchAll(PDO::FETCH_ASSOC);
				$count = 0;
				foreach ($rows as $row) {
					$count++;
					generateSongsInPlaylist($count, $row['album'], $row['songName'], $row['artistName'], $row['likes'], $row['songID'], $postPrimkey, $row['primkey']);
				}
			}
			$conn = null;
		}catch(PDOException $e) {
			echo "Error".$e->getMessage();
		}
	?>

	</table>
	
<?php
}

function generateBlankSong($primkey) {
?>
	<tr class="playlist-row blank">
		<td class="playlist-numbers"> - </td>
		<td> - </td>
		<td class="add-song" data-toggle="modal" data-target="#myModal" value="<?php echo $primkey; ?>">
			<i class="icon-plus-sign" style="margin-right:15px"></i> Add a song!
		</td>
	</tr>
<?php
}

function generateSongsInPlaylist($count, $album, $songName, $artistName, $likes, $songID, $postPrimkey, $primkey) {

if (!doesImageExist($album)) {
	$album = "img/defaultAlbum.png";
}

?>
	<tr class="playlist-row unselectable" id="<?php echo $postPrimkey; ?>-<?php echo $songID;?>" value="<?php echo $songID; ?>">
		<td class="playlist-numbers"><?php echo $count; ?></td>
		<td><a href="#" class="orange-link like-song-btn white-text" data="song<?php echo $primkey;?>" style="font-size:14px">
			<span class="song-likes"><?php echo $likes; ?></span> <i class="icon-thumbs-up-alt"></i>
		</a></td>
		<td><span class="selectable">
				<img src="<?php echo $album; ?>" /><?php echo $songName;?> - <?php echo $artistName; ?>
			</span>
		</td>
	</tr>
<?php
}

function generateIndividualPosts($likes, $songs, $message, $primkey, $conn, $count) {
	if($count == 0) {
		$specialClass = "first-post";
	}else{
		$specialClass = "";
	}
?>
					<section>
					<div class="post-container real-post <?php echo $specialClass; ?>" id="post<?php echo $primkey; ?>">
						<div class="status-info-box">
							<ul class="info">
								<a href="#" value="<?php echo $primkey; ?>" class="like-btn orange-link" title="Like this post">
									<li><?php echo $likes; ?></li>
									<li><i class="icon-thumbs-up-alt"></i></li>
								</a>
								<li style="border-left:1px solid #A0A0A0;border-right:1px solid #F0F0F0;padding:0"></li>
								<a href="#" title="Songs in playlist" class="playlist-count orange-link">
									<li style="margin-left:5px"><?php echo $songs; ?></li>
									<li><i class="icon-list"></i></li>
								</a>
							</ul>
						</div>
						<div class="status-container unselectable" id="<?php echo $primkey; ?>">
							<span class="selectable"><?php echo $message; ?></span>
						</div>

						<div class="status-box-triangle-border"></div>
						<div class="status-box-triangle"></div>

						<div class="collapse-post-container white-text-shadow">
								<a href="#" class = 'playlist-dropdown' title="Open playlist">
									<i class="icon-collapse icon-large collapse-post-icon" id="collapse<?php echo $primkey;?>"></i>
								</a>
						</div>
						<div class="vertical-line"></div>
						<div style="clear:both"></div>
					</div>
					<!--           ------              DropDown PlayList 					--------				-->
					<div class="dropdown-playlist-container">
							<span id="playlist<?php echo $primkey; ?>">
									<?php generatePlaylist($primkey,$conn); ?>
							</span>
							<div class="light-gradient bottom-playlist-bar" ></div>
					</div>
					</section>


<?php } ?>