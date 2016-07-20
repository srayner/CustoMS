<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['post_created-error'])){
		echo '<div class="alert alert-danger" role="alert">'.$_SESSION['msg']['post_created-error'].'</div>';
		unset($_SESSION['msg']['post_created-error']);
	}

	if(isset($_SESSION['msg']['post_created-success'])){
		echo '<div class="alert alert-success" role="alert">'.$_SESSION['msg']['post_created-success'].'</div>';
		unset($_SESSION['msg']['post_created-success']);
	}

?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" id="post">
	<fieldset>
		<legend>Create a post</legend>
		<div class="form-group">
			<label for="title">Title:</label>
			<input class="form-control" type="text" id="title" name="title" placeholder="Post title" required />
		</div>
		<div class="form-group">
			<label for="post">Post:</label>
			<textarea class="form-control md" id="post" rows="7" name="post" placeholder="Type your post here.." required /></textarea>
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
		<div class="form-group">
			<label for="category_content">Category:</label>
			<select class="form-control" id="category" name="category">
				<option value="Headlines">Headlines</option>
				<option value="National">National News</option>
				<option value="Local">Local News</option>
				<option value="General">General</option>
			</select>
		</div>
		<br />
		<input class="btn btn-primary btn-block" value="<?php echo ($_SESSION['role'] == 'journalist') ? 'Submit for review' : 'Publish'; ?>" type="submit" name="submit" />
	</fieldset>
</form>