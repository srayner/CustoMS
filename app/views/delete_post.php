<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php 

	if(isset($_SESSION['msg']['get_post_title_for_deletion-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['get_post_title_for_deletion-error'] . '</div>';
		unset($_SESSION['msg']['get_post_title_for_deletion-error']);
	}

	if(isset($_SESSION['msg']['post_delete-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['post_delete-error'] . '</div>';
		unset($_SESSION['msg']['post_delete-error']);
	}

?>

<?php if(isset($_SESSION['post_title_for_deletion']['title'])){ ?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
		<fieldset>
			<legend>Delete the '<?php echo $_SESSION['post_title_for_deletion']['title']; ?>' post created on <?php echo $_SESSION['post_title_for_deletion']['date_post_created'];?></legend>
			<input class="btn btn-danger btn-block" type="submit" name="submit" value="Delete post" />
		</fieldset>
	</form>
<?php } ?>