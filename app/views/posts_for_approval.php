<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['post_delete-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['post_delete-success'] . '</div>';
		unset($_SESSION['msg']['post_delete-success']);
	}

?>

<header class="page-header">
	<h1>Posts <small>waiting</small> approval</h1>
</header>

<?php

	if(isset($_SESSION['msg']['posts_for_approval-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['posts_for_approval-error'] . '</div>';
		unset($_SESSION['msg']['posts_for_approval-error']);
	}
	
	if(isset($_SESSION['msg']['posts_for_approval-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['posts_for_approval-success'] . '</div>';
		unset($_SESSION['msg']['posts_for_approval-success']);
	}
	
?>

<?php if(empty($_SESSION['posts_for_approval_results'])){ ?>
	<div class="alert alert-info" role="alert">There are no posts to approve.</div>
<?php } else{ ?>
	<table class="table table-striped" style="text-align: left;">
		<tr>
			<th>POST ID</th> <th>USERNAME</th> <th>EMAIL</th> <th>TITLE</th> <th>POST</th> <th>IMAGE</th> <th>CATEGORY</th> <th>CREATED</th> <th>LAST MODIFIED</th> <th>APPROVAL<br /><label for="select_all_checkboxes">Select All?</label> <input type="checkbox" id="select_all_checkboxes" value="Select All" /></th> <th></th>  <th></th>
		</tr>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<?php foreach($_SESSION['posts_for_approval_results'] AS $post){ ?>
				<?php 
					$id 	= $post['post_id']; 
					$url 	= $_SERVER['REQUEST_URI'];
					$image 	= $post['image'];
					$title 	= $post['title'];
				?> 
				<tr>
					<td><?php echo $post['post_id']; ?></td> <td><?php echo $post['username']; ?></td> <td><?php echo $post['email']; ?></td> <td><?php echo $post['title']; ?></td> <td><?php echo $post['post']; ?></td> <td><?php echo "<a class='fancybox' href='$image'><img src='$image' alt='$title-image' />"; ?></td> <td><?php echo $post['category']; ?></td> <td><?php echo $post['date_post_created']; ?></td> <td><?php echo $post['date_post_last_modified']; ?></td> <td><input type="checkbox" class="checkboxes_approval" name="post_ids_selected_for_approval[]" value="<?php echo $post['post_id']; ?>"></td> <td><?php echo "<a class='btn btn-info btn-sm' href='index.php?action=edit_post&amp;post_id=$id&amp;prev=$url'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a>"; ?> </td> <td><?php echo "<a class='btn btn-danger btn-sm' href='index.php?action=delete_post&amp;post_id=$id&amp;prev=$url'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>"; ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td><input class="btn btn-success" value="Approve Posts" type="submit" name="submit"></td> <td></td> <td></td>
			</tr>
		</form>
	</table>
<?php } ?>