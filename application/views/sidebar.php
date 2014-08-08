<aside>

<?php
if(!isset($right_menu)) {
	$right_menu = array();
}

if(isset($right_menu) && $isUserAdmin) {

	if(in_array('show_album', $right_menu)) {

		echo '
				<h2>
					Add pictures
				</h2>
				<div>
					<form method="post" action="" enctype="multipart/form-data">
						<input name="filesToUpload[]" id="filesToUpload" type="file" multiple=""/>
						<input name="alb_id" id="alb_id" type="hidden" value="'.$album->id.'" />
						<input name="uploadFiles" type="submit" value="Upload pictures" class="btn" />
					</form>
				</div>
			';

		echo '
				<h2>
					Change date (yyyy-mm-dd)
				</h2>
				<div>
					<form method="post" action="">
						<input name="alb_date" id="alb_date" type="date" value="'.$alb_date.'" style="width: auto;" />
						<input name="alb_id" id="alb_id" type="hidden" value="'.$album->id.'" />
						<input name="changeDate" type="submit" value="Change date" class="btn" />
					</form>
				</div>
			';

		echo '
				<h2>
					Rename album
				</h2>
				<div>
					<form method="post" action="">
						<input name="alb_title" id="alb_title" value="'.$album->title.'" />
						<input name="alb_id" id="alb_id" type="hidden" value="'.$album->id.'" />
						<input name="editTitle" type="submit" value="Rename album" class="btn" />
					</form>
				</div>
			';

		echo '
				<h2>
					Delete album
				</h2>
				<div>
					<form method="post" action="">
						<input name="alb_id" id="alb_id" type="hidden" value="'.$album->id.'" />
						<input name="deleteAlbum" type="submit" onClick="return confirmDeleteAlbum()" value="Delete album" class="btn" />
					</form>
				</div>
			<script>
			<!--
			function confirmDeleteAlbum() {
				var agree=confirm("Are you sure you want to delete this album?");
				if (agree)
					return true ;
				else
					return false ;
			}
			//-->
			</script>
			';

	}

	if(in_array('home', $right_menu)) {

		echo '
				<h2>
					Add album
				</h2>
				<div>
					<form method="post" action="">
						<input name="alb_title" id="alb_title" type="text"/>
						<input name="createAlbum" type="submit" value="Create album" class="btn" />
					</form>
				</div>
			';

	}

}

if($isUserAdmin) {

	echo '
			<h2>
				Admin
			</h2>
			<div>
				<a href="'.BASE_URL.'admin">Go to admin panel</a>
			</div>
		';

}

if($isConnected != null) {
	echo '
		<h2>
			Info
		</h2>
		<div>
			'.$isConnected.'<br/>
			<a href="'.BASE_URL.'user/login/out">Log out</a>
		</div>
	';
}

?>

</aside>