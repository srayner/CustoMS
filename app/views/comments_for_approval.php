<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['comment_deletetion-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['comment_deletetion-success'] . '</div>';
		unset($_SESSION['msg']['comment_deletetion-success']);
	}

?>

<header class="page-header">
	<h1>Comments <small>waiting</small> approval</h1>
</header>

<?php

	if(isset($_SESSION['msg']['comments_approval-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['comments_approval-error'] . '</div>';
		unset($_SESSION['msg']['comments_approval-error']);
	}
	
	if(isset($_SESSION['msg']['comments_for_approval-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['comments_for_approval-success'] . '</div>';
		unset($_SESSION['msg']['comments_for_approval-success']);
	}

?>

<?php if(empty($_SESSION['comments_for_approval_results'])){ ?>
	<div class="alert alert-info" role="alert">There are no comments to approve.</div>
<?php } else{ ?>
	<table class="table table-striped" style="text-align: left;">
		<tr>
			<th>COMMENT ID</th> <th>POST ID</th> <th>POST TITLE</th> <th>COMMENT TITLE</th> <th>NAME</th> <th>URL</th> <th>COMMENT</th> <th>CREATED</th> <th>APPROVAL<br /><label for="select_all_checkboxes">Select All?</label> <input type="checkbox" id="select_all_checkboxes" value="Select All" /></th> <th></th>
		</tr>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<?php foreach($_SESSION['comments_for_approval_results'] AS $comment){ ?>
				<?php 

					$id 	= $comment['comment_id']; 
					$url 	= $_SERVER['REQUEST_URI'];
				?>
				<tr>
					<td><?php echo $comment['comment_id']; ?></td> <td><?php echo $comment['post_id']; ?></td> <td><?php echo $comment['title']; ?></td> <td><?php echo $comment['comment_title']; ?></td> <td><?php echo $comment['name']; ?></td> <td><?php echo $comment['url']; ?></td> <td><?php echo $comment['comment']; ?></td> <td><?php echo $comment['date_comment_created']; ?></td> <td><input type="checkbox"class="checkboxes_approval"  name="comments_selected_for_approval[]" value="<?php echo $comment['comment_id']; ?>"></td> <td><?php echo "<a class='btn btn-danger btn-sm' href='index.php?action=delete_comment&amp;comment_id=$id&amp;prev=$url'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>"; ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td><input class="btn btn-success" value="Approve Comments" type="submit" name="submit"></td> <td></td> <td></td>
			</tr>
		</form>
	</table>
<?php } ?>