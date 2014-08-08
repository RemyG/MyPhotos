<header>
	<h2>Albums</h2>
</header>
	
<div class="section-content">

<?php 

foreach ($list_albums as $album) {
	
	echo '
	<div class="album-thumbnail">
		<a href="album/show/'.$album->hashname.'">
		<div class="picture">';
	if($album->cover) {
		echo '<img src="'.BASE_URL.PICTURE_PATH.COVER_DIR.$album->cover->url.'" alt="'.$album->title.'">';			
	} else {
		echo '<p>This album has no cover picture.</p>';
	}
	echo '			
		</div>
		<div class="description">
			<h4>'.$album->title.'</h4>
			<p>'.getDateFromTimestamp($album->date_crea).'</p>
		</div>
		</a>
	</div>';
			
}

?>

</div>