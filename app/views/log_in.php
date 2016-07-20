<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['log_in-err'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['log_in-err'] . '</div>';
		unset($_SESSION['msg']['log_in-err']);
	}

?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Log in to your account</legend>
		<div class="form-group">
			<label for="email">Email Address:</label>
			<input class="form-control" id="email" type="email" name="email" placeholder="Email address" required />
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input class="form-control" id="password" type="password" name="password" placeholder="Password" required/>
			<a href="index.php?action=request_password_change">Request Password Change</a>
		</div>
		<input class="btn btn-primary btn-block" type="submit" name="submit" value="Log in" />
	</fieldset>
</form>