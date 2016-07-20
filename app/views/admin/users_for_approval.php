<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<header class="page-header">
	<h1>Users <small>waiting</small> approval</h1>
</header>

<?php
	
	if(isset($_SESSION['msg']['users_for_approval-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['users_for_approval-success'] . '</div>';
		unset($_SESSION['msg']['users_for_approval-success']);
	}

		if(isset($_SESSION['msg']['users_for_approval-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['users_for_approval-success'] . '</div>';
		unset($_SESSION['msg']['users_for_approval-success']);
	}
	
?>

<?php if(empty($_SESSION['users_for_approval_results'])){ ?>
	<div class="alert alert-info" role="alert">There are no users to approve.</div>
<?php } else{ ?>
	<table class="table table-striped" style="text-align: left;">
		<tr>
			<th>USER ID</th> <th>USERNAME</th> <th>EMAIL</th> <th>ROLE</th> <th>CREATED</th> <th>APPROVAL<br /><label for="select_all_checkboxes">Select All?</label> <input type="checkbox" id="select_all_checkboxes" value="Select All" /></th> <th></th>
		</tr>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<?php foreach($_SESSION['users_for_approval_results'] AS $user){ ?>
				<?php 

					$id = $user['user_id'];
					$url = $_SERVER['REQUEST_URI']; 

				?>
				<tr>
					<td><?php echo $user['user_id']; ?></td> <td><?php echo $user['username']; ?></td> <td><?php echo $user['email']; ?></td> <td><?php echo $user['role']; ?></td> <td><?php echo $user['date_user_created']; ?></td> <td><input type="checkbox" class="checkboxes_approval" name="user_ids_selected_for_approval[]" value="<?php echo $user['user_id']; ?>"></td> <td><?php echo "<a class='btn btn-danger btn-sm' href='index.php?action=delete_user&amp;user_id=$id&amp;prev=$url'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>"; ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td><input class="btn btn-success" value="Approve Users" type="submit" name="submit"></td> <td></td>
			</tr>
		</form>
	</table>
<?php } ?>