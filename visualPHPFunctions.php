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
		foreach ($allData as $row) {
			generateIndividualPosts($row['likes'], $row['songs'], $row['post'], $row['primkey'], $conn);
		}
		$conn = null;
	}catch(PDOException $e) {
		echo "Error".$e->getMessage();
	}
}

function generatePlaylist($primkey,$conn) {
?>
	<table class="inverted-darker-gradient">
		<colgroup>
		<col style="width: 54px">
		<col style="width: 84px">
		<col>
		<col>
		</colgroup>
		<tr class="white-text-shadow light-gradient">
			<th>#</th><th>Likes</th><th>Song/Artist</th><th>Note From Author</th>
		</tr>

	<?php
		try {
			$data = $conn->prepare('SELECT * FROM Playlists WHERE postPrimkey = ? ORDER BY likes DESC, time DESC');
			$data->execute(array($primkey));
			$rowCount = $data->rowCount();
			generateBlankSong($primkey);
			if ($rowCount > 0) {
				$rows = $data->fetchAll(PDO::FETCH_ASSOC);
				$count = 0;
				foreach ($rows as $row) {
					$count++;
					generateSongsInPlaylist($count, $row['album'], $row['songName'], $row['artistName'], $row['authorComment'], $row['likes'], $row['songID'], $primkey);
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
		<td> - </td>
	</tr>
<?php
}

function generateSongsInPlaylist($count, $album, $songName, $artistName, $authorComment, $likes, $songID, $postPrimkey) {

if (!doesImageExist($album)) {
	$album = "img/defaultAlbum.png";
}

?>
	<tr class="playlist-row unselectable" id="<?php echo $postPrimkey; ?>-<?php echo $songID;?>" value="<?php echo $songID; ?>">
		<td class="playlist-numbers"><?php echo $count; ?></td>
		<td><?php echo $likes; ?> <i class="icon-thumbs-up-alt"></i></td>
		<td><span class="selectable">
				<img src="<?php echo $album; ?>" /><?php echo $songName;?> - <?php echo $artistName; ?>
			</span>
		</td>
		<td>"<?php echo $authorComment; ?>"</td>
	</tr>
<?php
}

function generateIndividualPosts($likes, $songs, $message, $primkey, $conn) {
?>
					<section>
					<div class="post-container real-post" id="post<?php echo $primkey; ?>">
						<div class="status-info-box">
							<ul class="info">
								<a href="#" value="<?php echo $primkey; ?>" class="like-btn" title="Like this post">
									<li><?php echo $likes; ?></li>
									<li><i class="icon-thumbs-up-alt"></i></li>
								</a>
								<li style="border-left:1px solid #A0A0A0;border-right:1px solid #F0F0F0;padding:0"></li>
								<a href="#" title="Songs in playlist" class="playlist-count">
									<li style="margin-left:5px"><?php echo $songs; ?></li>
									<li><i class="icon-list"></i></li>
								</a>
							</ul>
						</div>
						<div class="status-container unselectable" id="<?php echo $primkey; ?>">
							<span class="selectable"><?php echo $message; ?></span>
						</div>
						<div class="collapse-post-container white-text-shadow">
								<a href="#" class = 'playlist-dropdown' title="Open playlist">
									<i class="icon-collapse icon-large collapse-post-icon" id="collapse<?php echo $primkey;?>"></i>
								</a>
						</div>
						<div class="vertical-line"></div>
						<!-- <div class="status-box-triangle-border"></div>
						<div class="status-box-triangle"></div> -->
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