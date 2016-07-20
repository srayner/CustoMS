<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<header class="page-header">
	<h1>All Users</h1>
</header>

<?php if(empty($_SESSION['get_all_users_results'])){ ?>
	<div class="alert alert-info" role="alert">There are no users to show.</div>
<?php } else{ ?>
	<table class="table table-striped" style="text-align: left;">
		<tr>
			<th>USER ID</th> <th>USERNAME</th> <th>EMAIL</th> <th>ROLE</th> <th>CREATED</th> <th></th>
		</tr>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<?php foreach($_SESSION['get_all_users_results'] AS $user){ ?>
				<?php 

					$id = $user['user_id'];
					$url = $_SERVER['REQUEST_URI']; 

				?>
				<tr>
					<td><?php echo $user['user_id']; ?></td> <td><?php echo $user['username']; ?></td> <td><?php echo $user['email']; ?></td> <td><?php echo $user['role']; ?></td> <td><?php echo $user['date_user_created']; ?></td> <td><?php echo "<a class='btn btn-danger btn-sm' href='index.php?action=delete_user&amp;user_id=$id&amp;prev=$url'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete</a>"; ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
		</form>
	</table>
<?php } ?>