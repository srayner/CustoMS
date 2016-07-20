<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php 

	if(isset($_SESSION['msg']['get_comment_title_for_deletion-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['get_comment_title_for_deletion-error'] . '</div>';
		unset($_SESSION['msg']['get_comment_title_for_deletion-error']);
	}

	if(isset($_SESSION['msg']['comment_deletetion-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['comment_deletetion-error'] . '</div>';
		unset($_SESSION['msg']['comment_deletetion-error']);
	}

?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<fieldset>
		<legend>Delete the '<?php echo $_SESSION['comment_title_for_deletion']['comment_title']; ?>' comment created on <?php echo $_SESSION['comment_title_for_deletion']['date_comment_created'];?></legend>
		<input class="btn btn-danger btn-block" type="submit" name="submit" value="Delete Comment" />
	</fieldset>
</form>