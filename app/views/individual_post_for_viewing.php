<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['individual_posts_viewing-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['individual_posts_viewing-error'] . '</div>';
		unset($_SESSION['msg']['individual_posts_viewing-error']);
	}

	/*if(isset($_SESSION['msg']['post_deletetion-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['post_deletetion-success'] . '</div>';
		unset($_SESSION['msg']['post_deletetion-success']);
	}*/

	if(isset($_SESSION['msg']['comment_deletetion-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['comment_deletetion-success'] . '</div>';
		unset($_SESSION['msg']['comment_deletetion-success']);
	}

	if(isset($_SESSION['msg']['create_comment-error'])){
		echo '<div id="comment_msg" class="alert alert-danger" role="alert">' . $_SESSION['msg']['create_comment-error'] . '</div>';
		unset($_SESSION['msg']['comment_created-error']);
	}

	if(isset($_SESSION['msg']['create_comment-success'])){
		echo '<div id="comment_msg" class="alert alert-success" role="alert">' . $_SESSION['msg']['create_comment-success'] . '</div>';
		unset($_SESSION['msg']['create_comment-success']);
	}

?>

<?php if($_SESSION['individual_posts_for_viewing_results'] != null){ ?>
	<?php
		$id 			= $_SESSION['individual_posts_for_viewing_results']['post_id'];
		$username 		= $_SESSION['individual_posts_for_viewing_results']['username'];
		$email 			= $_SESSION['individual_posts_for_viewing_results']['email'];
		$email_hash 	= md5(strtolower(trim($email)));
		$image_element 	= "";
		$image 			= $_SESSION['individual_posts_for_viewing_results']['image'];
		$title 			= $_SESSION['individual_posts_for_viewing_results']['title'];
		$category 		= $_SESSION['individual_posts_for_viewing_results']['category'];
		if($image != ""){
			$image_element = "<a class='fancybox' href='$image'><img src='$image' alt='$title-image' /></a>";
		}
		$url 		= $_SERVER['REQUEST_URI'];
	?>
	<section>
		<article>
			<header><h1 class="post-title"><?php echo $title; ?></h1></header>
			<div class="byline"><?php echo "<a href='http://www.gravatar.com/$email_hash'><img src='http://www.gravatar.com/avatar/$email_hash?s=40' /></a>"; ?> by <strong><?php echo "<a href='index.php?action=view_user_posts&username=$username'>$username</a>"; ?></strong> in <strong><?php echo "<a href='index.php?action=view_category_posts&category=$category'>$category</a>"; ?></strong>.<br />Last Modified: <strong><?php echo $_SESSION['individual_posts_for_viewing_results']['date_post_last_modified']; ?></strong></div>
			<div class="post-image"><?php echo $image_element; ?></div>
			<div class="post-content"><?php echo $_SESSION['individual_posts_for_viewing_results']['post']; ?></div>
			<?php
				if($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'admin'){
					echo "<a href='index.php?action=edit_post&amp;post_id=$id&amp;prev=$url&amp;view=individual' class='btn btn-info btn-sm'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a>&nbsp";
					echo "<a href='index.php?action=delete_post&amp;post_id=$id&amp;prev=$url&amp;view=individual' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>";
				}
			?>	
			<hr />
		</article>
		<!--<button style="margin-bottom: 7px;" class="btn btn-default btn-lg" id="collapse_comments" data-toggle="collapse" data-target="#comments">Hide Comments</button>-->
		<div id="comments"> <!-- class="collapse" -->
			<?php if($_SESSION['approved_comment_details'] != null){ ?>
				<h3 style="color: #333;">Comments:</h3>
				<?php foreach($_SESSION['approved_comment_details'] AS $comment){ ?>
					<div class="comment">
						<div class="comment-title"><strong><?php echo $comment['comment_title']; ?></strong>
							<br />by
							<?php if($comment['url'] != ""){ ?>
								<a href="<?php echo $comment['url']; ?>">
									<?php echo $comment['name']; ?>
								</a>
							<?php } else{ ?>
								<?php echo $comment['name']; ?>
							<?php } ?>
							on <?php echo $comment['date_comment_created'];?>.
						</div>
						<div class="comment-content"><?php echo $comment['comment']; ?></div>
						<br />
						<?php
							$id 	= $comment['comment_id'];
							$url 	= $_SERVER['REQUEST_URI'];
							if($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'admin'){
								echo "<a href='index.php?action=delete_comment&amp;comment_id=$id&amp;prev=$url&amp;view=individual' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>";
							}
						?>	
					</div>
				<?php } ?>
				
			<?php } else{ ?>
				<strong style="color: #333;">No comments..</strong>
			<?php } ?>
		</div>
		<div id="comment-creation">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
				<fieldset>
					<legend>Leave a comment</legend>
					<div class="form-group">
						<label for="name">Name:</label>
						<input class="form-control" id="name" type="text" name="name" placeholder="Name" value="<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : '' ); ?>" required />
					</div>
					<div class="form-group">
						<label for="url">URL:</label>
						<input class="form-control" type="url" id="url" name="url" placeholder="http://yourwebsite.com" />
					</div>
					<div class="form-group">
						<label for="title">Title:</label>
						<input class="form-control" type="text" id="title" name="title" placeholder="Comment title" required />
					</div>
					<div class="form-group">
						<label for="comment">Comment:</label>
						<textarea class="form-control" id="comment" name="comment" placeholder="Type your comment here.." required /></textarea>
					</div>
					<input class="btn btn-primary btn-block" value="Add Comment" type="submit" name="submit" />
				</fieldset>
			</form>
		</div>
	</section>
<?php } ?>