
<div class="section">

	<div class="section_title">
		<h2>Admin</h2>
	</div>
	
	
	<div class="section_content">
		<h3>Users</h3>
		<?php 
		foreach($list_users as $user) {
			echo "<div><a href='admin/user/".$user->hash."'>".$user->hash."</a> (".$user->desc.")</div>";
		}
		?>
		<form method="post" action="">
			Name <input type="text" name="user_name" />
			<input type="submit" name="createUser" value="Create user" />
		</form>
	</div>
	
</div>

