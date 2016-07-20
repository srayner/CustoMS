<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['post_delete-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['post_delete-success'] . '</div>';
		unset($_SESSION['msg']['post_delete-success']);
	}

	if(isset($_SESSION['msg']['comment_delete-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['comment_delete-success'] . '</div>';
		unset($_SESSION['msg']['comment_delete-success']);
	}

?>

<?php if(!isset($_SESSION['username'])){ ?>
	<div class="jumbotron">
		<header><h1>Welcome to the Austro-Asian Times!</h1></header>
		<p>Become a journalist with our new online news platform.</p>
		<p><a href="index.php?action=sign_up" class="btn btn-primary btn-lg" role="button">Sign Up</a></p>
	</div>
<?php } else{ ?>
	<header class="page-header">
		<h1>The Latest News <small>articles</small></h1>
	</header>
<?php } ?>

<?php

	if(isset($_SESSION['msg']['top_five_posts_for_viewing_results-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['top_five_posts_for_viewing_results-error'] . '</div>';
		unset($_SESSION['msg']['top_five_posts_for_viewing_results-error']);
	}

?>

<?php if($_SESSION['top_five_posts_for_viewing_results'] != null){ ?>
	<section>
		<?php foreach($_SESSION['top_five_posts_for_viewing_results'] AS $post){ ?>
			<?php 
				$post_id 		= $post['post_id']; 
				$title 			= $post['title'];
				$username 		= $post['username'];
				$email 			= $post['email'];
				$email_hash 	= md5(strtolower(trim($email)));
				$image 			= $post['image'];
				$image_element 	= "";
				$category 		= $post['category'];
				$after_excerpt 	= "";
				if(strlen($post['post']) > 200 && extension_loaded('mbstring')){
					$after_excerpt = "&hellip;";
				}
				if(extension_loaded('mbstring')){ 
					$post['post'] = mb_strimwidth($post['post'], 0, 200);
				}
				if($image != ""){
					$image_element = "<a class='fancybox' href='$image'><img src='$image' alt='$title-image' /></a>";
				}
			?>
			<article>
				<header><h2 class="post-title"><?php echo "<a href='index.php?action=view_post&post_id=$post_id'>$title</a>"; ?></h2></header>
				<span class="byline"><?php echo "<a href='http://www.gravatar.com/$email_hash'><img src='http://www.gravatar.com/avatar/$email_hash?s=40' /></a>"; ?> by <strong><?php echo "<a href='index.php?action=view_user_posts&username=$username'>$username</a>"; ?></strong> in <strong><?php echo "<a href='index.php?action=view_category_posts&category=$category'>$category</a>"; ?></strong>.<br />Last Modified: <strong><?php echo $post['date_post_last_modified']; ?></strong></span>
				<div class="post-image"><?php echo $image_element; ?></div>
				<div class="post-content"><?php echo $post['post'] . "$after_excerpt<br /><a href='index.php?action=view_post&post_id=$post_id' class='goto'>Go to post&rarr;</a>"; ?></div>
				<?php
					if($_SESSION['role'] == 'editor'){
						echo "<a href='index.php?action=edit_post&amp;post_id=$post_id&amp;prev=/' class='btn btn-info btn-sm'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a>&nbsp";
						echo "<a href='index.php?action=delete_post&amp;post_id=$post_id&amp;prev=/' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>";
					}
				?>
				<hr />
			</article>
		<?php } ?>
	</section>
<?php } ?>