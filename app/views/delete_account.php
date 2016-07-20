<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['delete_account-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['delete_account-error'] . '</div>';
		unset($_SESSION['msg']['delete_account-error']);
	}
	if(isset($_SESSION['msg']['delete_account-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['delete_account-success'] . '</div>';
		unset($_SESSION['msg']['delete_account-success']);
	}
	
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Confirm deletion of your account</legend>
		<div class="form-group">
			<label for="password">Password: </label>
			<input class="form-control" type="password" id="password" name="password" />		
		</div>
		<br />
		<input class="btn btn-danger btn-block" type="submit" name="submit" value="Delete My Account" />							
	</fieldset>
</form>