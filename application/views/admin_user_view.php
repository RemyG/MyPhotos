
<div class="section">

	<div class="section_title">
		<h2>User - <?php echo "User ".$user->hash; ?></h2>
	</div>
	
	
	<div class="section_content">
		<h3>Albums</h3>

		<form method="post" action="">
		
		<?php
		
		foreach($list_albums as $album) {
			echo "<input type='checkbox' name='albums[]' value='".$album->id."' ".(in_array($album->id, $user_albums_id) ? "checked='checked'" : "")."/>".$album->title."<br/>";
		}
		
		?>
		
		<input type="submit"/>
		
		</form>
		
		<h3>Description</h3>

		<form method="post" action="">
		
		<?php
		
			echo "<input type='text' name='desc' value='".$user->desc."'/><br/>";
		
		?>
		
		<input type="submit" name="updateDesc" />
		
		</form>
		
		<h3>Password</h3>

		<form method="post" action="">
		
		<?php
		
			echo "<input type='password' name='password' /><br/>";
			echo "<input type='password' name='password2' /><br/>";
		
		?>
		
		<input type="submit" name="updatePassword" />
		
		</form>
		
		<h3>Admin</h3>

		<form method="post" action="">
		
		<?php
		
			echo "<input type='checkbox' name='setAdmin' ".($user->admin == 1 ? "checked='checked'" : "")." /> Set Admin<br/>";
		
		?>
		
		<input type="submit" name="updateAdmin" />
		
		</form>

	</div>
	
</div>