<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<header class="page-header">
	<h1>Your <small>dashboard</small></h1>
</header>

<?php if(empty($_SESSION['get_user_details_for_dashboard'])){ ?>
	<div class="alert alert-info" role="alert">There are no details to show.</div>
<?php } else{ ?>
	<?php foreach($_SESSION['get_user_details_for_dashboard'] AS $user){ ?>
		<?php 

			$id 		= $user['user_id'];
			$url 		= $_SERVER['REQUEST_URI']; 
			$username 	= $user['username'];

		?>
		<div class="row">
			<div class="col-md-6">
				<h2>User details:</h2>
				<ul class="list-group">
					<span><strong>User ID:</strong></span>
					<li class="list-group-item"><?php echo $user['user_id']; ?></li>
					<span><strong>Username:</strong></span>
					<li class="list-group-item"><?php echo $user['username']; ?></li>
					<span><strong>Email Address:</strong></span>
					<li class="list-group-item"><?php echo $user['email']; ?></li>
					<span><strong>Role:</strong></span>
					<li class="list-group-item"><?php echo $user['role']; ?></li>
					<span><strong>Date Account  Created:</strong></span>
					<li class="list-group-item"><?php echo $user['date_user_created']; ?></li>
				</ul>
			</div>
			<div class="col-md-6">
				<ul class="nav nav-pills nav-stacked">
					<?php if($_SESSION['role'] == "admin"): ?>
						<h3>Administrative actions</h3>
						<li role="presentation"><a href="index.php?action=show_users">Display all Users</a></li>
						<li role="presentation"><a href="index.php?action=approve_users">Approve Users</a></li>
					<?php endif; ?>

					<?php if($_SESSION['role'] == "editor" || $_SESSION['role'] == "admin"): ?>
						<h3>Editorial actions</h3>
						<li role="presentation"><a href="index.php?action=approve_posts">Approve Posts</a></li>
						<li role="presentation"><a href="index.php?action=approve_comments">Approve Comments</a></li>
					<?php endif; ?>

					<h3>Post actions</h3>
					<li role="presentation"><a href="index.php?action=create_post">Create a Post</a></li>
					<li role="presentation"><a href="index.php?action=view_user_posts&username=$username">View your posts</a></li>
					
					<h3>Account actions</h3>
					<li role="presentation"><a href="index.php?action=request_password_change">Request Password Change</a></li>
					<?php if($_SESSION['role'] != "admin"): ?>
						<li role="presentation"><a href="index.php?action=delete_account">Delete Account</a></li>
						<li role="presentation"><a href="index.php?action=contact_us">Contact Us</a></li>
					<?php endif; ?>
				</ul>	
			</div>
		</div>
	<?php } ?>
<?php } ?>