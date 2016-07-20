<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['request_password_change-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['request_password_change-error'] . '</div>';
	    unset($_SESSION['msg']['request_password_change-error']);
	}

	if(isset($_SESSION['msg']['request_password_change-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['request_password_change-success'] . '</div>';
		unset($_SESSION['msg']['request_password_change-success']);
	}	

?>               

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Request a password reset</legend>
		<div class="form-group">
			<label for="username">Username:</label>
			<input class="form-control" id="username" type="text" name="username" placeholder="Username" required/>
		</div>
		<div class="form-group">
			<label for="email">Email Address:</label>
			<input class="form-control" id="email" type="email" name="email" placeholder="Email address" required/>
		</div>
		<input class="btn btn-primary btn-block" type="submit" name="submit" value="Request Password Change" />							
	</fieldset>
</form>