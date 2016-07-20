<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php 

	if(isset($_SESSION['msg']['get_user_for_deletion-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['get_user_for_deletion-error'] . '</div>';
		unset($_SESSION['msg']['get_user_for_deletion-error']);
	}

	if(isset($_SESSION['msg']['user_deletetion-success'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['user_deletetion-success'] . '</div>';
		unset($_SESSION['msg']['user_deletetion-success']);
	}

	if(isset($_SESSION['msg']['user_deletetion-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['user_deletetion-error'] . '</div>';
		unset($_SESSION['msg']['user_deletetion-error']);
	}

?>

<?php if(isset($_SESSION['user_for_deletion']['username'])){ ?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
		<fieldset>
			<legend>Delete '<?php echo $_SESSION['user_for_deletion']['username']; ?>', which was created on <?php echo $_SESSION['user_for_deletion']['date_user_created'];?></legend>
			<input class="btn btn-danger btn-block" type="submit" name="submit" value="Delete user" />
		</fieldset>
	</form>
<?php } ?>