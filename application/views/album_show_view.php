<header>
	<h2>Album - <?php echo $album->title; ?></h2>
</header>

<div class="section-content">

	<div class="album-date">
		<?php echo getDateFromTimestamp($album->date_crea); ?>
	</div>

	<form method="post" action="">

	<?php

	foreach ($list_pictures as $picture) {

		$isCover = ($isUserAdmin && $picture->cover == 1) ? " cover" : "";

		echo '
			<div class="picture-thumbnail">
				<a href="'.BASE_URL.PICTURE_PATH.$picture->url.'" rel="prettyPhoto[pp_gal]" title="'.$picture->id.'">
				<div class="picture'.$isCover.'">
					<img src="'.BASE_URL.PICTURE_PATH.THUMB_DIR.$picture->url.'" alt="'.$picture->desc.'" id="thumb-'.$picture->id.'">
					';
		if ($isUserAdmin) {
			echo '<a class="rotate-btn rotate-left" data-id="'.$picture->id.'"><i class="fa fa-rotate-left fa-2x"></i></a>
						<a class="rotate-btn rotate-right" data-id="'.$picture->id.'"><i class="fa fa-rotate-right fa-2x"></i></a>';
		}

		echo '
			</div>
				'.(!$isUserAdmin && $picture->desc != null && $picture->desc != '' ? '<div class="description">'.$picture->desc.'</div>' : '').'
				</a>';


		if($isUserAdmin) {
			echo '
			<div class="show_picture_admin">
				<div class="control-group">
					<div class="controls">
						<input type="text" name="desc_'.$picture->id.'" value="'.$picture->desc.'" id="desc_'.$picture->id.'" maxlength="200" />
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<a class="delete btn" data-id="'.$picture->id.'"><i class="fa fa-trash-o"></i></a>
						<a class="make-cover btn" data-id="'.$picture->id.'"><i class="fa fa-dot-circle-o"></i></a>
					</div>
				</div>
			</div>';
		}
		echo '
			</div>';

	}

	?>

	<input type="hidden" name="alb_id" value="<?php echo $album->id; ?>" />

	<?php
	if($isUserAdmin) {
		echo '
		<div class="clear">
			<input type="submit" name="saveChanges" onClick="return confirmSubmitChanges()"/>
		</div>';
	}
	?>

	</form>

</div>

<script>
<!--
function confirmSubmitChanges() {
	var count = $("[type='checkbox']:checked").length;
	if(count > 0) {
		var agree=confirm("Are you sure you want to delete " + count + " pictures?");
		if (agree)
			return true ;
		else
			return false ;
	}
	return true;
}

$("a.rotate-left").click(function() {
	var picId = $(this).attr("data-id");
	$.ajax({
		type: "GET",
		url: "/album/rotateLeft/" + picId
	}).done(function( msg ) {
		$("#thumb-"+picId).attr("src", "<?php echo BASE_URL.PICTURE_PATH.THUMB_DIR; ?>" + msg);
	});
});

$("a.rotate-right").click(function() {
	var picId = $(this).attr("data-id");
	$.ajax({
		type: "GET",
		url: "/album/rotateRight/" + picId
	}).done(function( msg ) {
		$("#thumb-"+picId).attr("src", "<?php echo BASE_URL.PICTURE_PATH.THUMB_DIR; ?>" + msg);
	});
});

$("a.delete").click(function() {
	var picId = $(this).attr("data-id");
	$.ajax({
		type: "GET",
		url: "/album/delete/" + picId,
		dataType: "json"
	}).done(function(data) {
		if (data.error != null) {
			alert(data.error);
		}
		else if (data.success != null) {
			$("#thumb-"+picId).parents("div.picture-thumbnail").remove();
		}
	});
});

$("a.make-cover").click(function() {
	var picId = $(this).attr("data-id");
	$.ajax({
		type: "GET",
		url: "/album/makeCover/<?php echo $album->id; ?>/" + picId
	}).done(function( msg ) {
		$("div.picture").removeClass("cover");
		$("#thumb-"+picId).parents("div.picture").addClass("cover");
	});
});

//-->
</script>
