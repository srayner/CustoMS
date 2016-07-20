<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	$greeting = "Hi ";
	$signed_in_as = "";
	$role_greeting = "<div style='text-align:center; padding: 8px;'>";

	if(isset($_SESSION['user_id']) && isset($_COOKIE['user']) && $_COOKIE['user'] == hash_hmac('whirlpool', $_SESSION['username'].$_SESSION['user_id'], COOKIE_SALT)){
		$greeting .= $_SESSION['username'] . '!';
		$signed_in_as .= "You are signed in as " . ($_SESSION['role'] == 'admin' ? '' : ($_SESSION['role'] == 'editor' ? "an" : "a")) . " ";
		$role_greeting .= $signed_in_as;
		$role_greeting .= $_SESSION['role'] . ".</div>";
	} else{
		$greeting .= "Guest!";
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8">
		<title>Austro-Asian Times</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="https://s3-ap-southeast-2.amazonaws.com/ashleymenhennett/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<link rel="stylesheet" href="includes/assets/vendor/fancyBox/source/jquery.fancybox.css?v=2.1.5">
		<link rel="stylesheet" href="includes/assets/vendor/bootstrap-wysihtml5.css">
		<link rel="stylesheet" href="includes/assets/css/style.css">
		<!--[if lt IE 9]>
	  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	  	<![endif]-->
	  	<!--[if lt IE 9]>
	  		<script src="https://raw.githubusercontent.com/scottjehl/Respond/master/src/respond.js"></script>
	  	<![endif]-->
	</head>
	<body>			
		<main class="container-fluid">
			<nav class="navbar navbar-default">
			  	<div class="container-fluid">
			    	<div class="navbar-header">
			    		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			        		<span class="sr-only">Toggle navigation</span>
			        		<span class="icon-bar"></span>
				       		<span class="icon-bar"></span>
			        		<span class="icon-bar"></span>
			      		</button>
			      		<a class="navbar-brand" href="index.php">Austro-Asian Times</a>
			    	</div>
			    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			    		<ul class="nav navbar-nav">
							<?php if(isset($_SESSION['user_id']) && isset($_COOKIE['user']) && $_COOKIE['user'] == hash_hmac('whirlpool', $_SESSION['username'].$_SESSION['user_id'], COOKIE_SALT)): ?>
								<li><a href="index.php?action=create_post"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Create Post</a></li>
							<?php endif; ?>
			      			<li class="dropdown">
			      				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> Categories <span class="caret"></span></a>
				      			<ul class="dropdown-menu">
					            	<li><a href="index.php?action=view_category_posts&category=Headlines">Headlines</a></li>
					            	<li><a href="index.php?action=view_category_posts&category=National&nbsp;News">National News</a></li>
				        			<li><a href="index.php?action=view_category_posts&category=Local&nbsp;News">Local News</a></li>
				            		<li><a href="index.php?action=view_category_posts&category=General">General</a></li>
			          			</ul>
			      			</li>
			      			<li>
								<form action="index.php" method="GET" class="navbar-form" role="search">
									<input type="hidden" name="action" value="view_search_posts" />
								    <input class="form-control" type="search" name="keyword" placeholder="Search" />
								    <input class="btn btn-default" type="submit" value="Search" />
								</form>
							</li>
			      		</ul>
			      		<ul class="nav navbar-nav navbar-right">
			        		<li class="dropdown">
			          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $greeting; ?> <span class="caret"></span></a>
			          			<ul class="dropdown-menu">
									<?php if(isset($_SESSION['user_id']) && isset($_COOKIE['user']) && $_COOKIE['user'] == hash_hmac('whirlpool', $_SESSION['username'].$_SESSION['user_id'], COOKIE_SALT)): ?>
										<li><?php echo $role_greeting; ?></li>
										<li role="separator" class="divider"></li>
							      		<?php $user_id = $_SESSION['user_id']; ?>
				            			<li><a href="index.php?action=show_dashboard&user_id=<?php echo $user_id; ?>"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Dashboard</a></li>
				            			<li><a href="index.php?action=log_out"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Log out</a></li>
			        				<?php else: ?>
			        					<li><a href="index.php?action=log_in"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Log in</a></li>
			            				<li><a href="index.php?action=sign_up"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Sign up</a></li>
			            				<li><a href="index.php?action=request_password_change"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Request Password Change</a></li>
			        				<?php endif; ?>
			          			</ul>
			        		</li>
			      		</ul>
			    	</div>
			  	</div>
			</nav>