<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php 

	if(isset($_SESSION['msg']['get_post_details_for_edit-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['get_post_details_for_edit-error'] . '</div>';
		unset($_SESSION['msg']['get_post_details_for_edit-error']);
	}

	if(isset($_SESSION['msg']['post_update-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['post_update-error'] . '</div>';
		unset($_SESSION['msg']['post_update-error']);
	}

?>

<?php if(isset($_SESSION['post_details_for_edit'])){ ?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST"  enctype="multipart/form-data" id="post">
		<fieldset>
			<legend>Edit post</legend>
			<div class="form-group">
				<label for="title">Title:</label>
				<input class="form-control" type="text" id="title" name="title" value="<?php echo $_SESSION['post_details_for_edit']['title']; ?>">
			</div>
			<div class="form-group">
				<label for="post">Post:</label>
				<textarea class="form-control" id="post" rows="7" name="post"><?php echo $_SESSION['post_details_for_edit']['post']; ?></textarea>
			</div>
			<div class="form-group">
				<label for="image">Image:</label>
				<input type="file" class="form-control" id="image" name="image" />
			</div>
			<div style="font-style: italic;">
				NOTE: Image will be added to the top of your post. 
				<br />
				Please make sure your image has a unique file name and is no larger than 2MB.
				<br />
				<strong>ONLY .jpg, .jpeg and .png allowed.</strong>
			</div>
			<br />
			<?php if($_SESSION['post_details_for_edit']['image'] != ""){?>
				<div>
					<strong>Current image:</strong>
					<br />
					<img src="<?php echo $_SESSION['post_details_for_edit']['image']; ?>" alt="<?php echo $_SESSION['post_details_for_edit']['title'] . '-image'; ?>" />
				</div>
				<br />
			<?php } ?>
			<div class="form-group">
				<label for="category">Category:</label>
				<select class="form-control" id="category" name="category">
					<option value="Headlines" <?php if($_SESSION['post_details_for_edit']['category'] == "Headlines"){echo "selected"; } ?>>Headlines</option>
					<option value="National" <?php if($_SESSION['post_details_for_edit']['category'] == "National"){echo "selected"; } ?>>National News</option>
					<option value="Local" <?php  if($_SESSION['post_details_for_edit']['category'] == "Local"){echo "selected"; } ?>>Local News</option>
					<option value="General" <?php  if($_SESSION['post_details_for_edit']['category'] == "General"){echo "selected"; } ?>>General</option>
				</select>
			</div>
			<strong>Created:</strong> <?php echo $_SESSION['post_details_for_edit']['date_post_created']; ?>. <strong>Last Modified:</strong> <?php echo $_SESSION['post_details_for_edit']['date_post_last_modified']; ?>
			<br />
			<br />
			<input class="btn btn-primary btn-block" type="submit" name="submit" value="Update post" />
		</fieldset>
	</form>
<?php } ?>