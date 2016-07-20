<?php if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..'); ?>

<?php

	if(isset($_SESSION['msg']['user_verification-error'])){
		echo '<div class="alert alert-danger" role="alert">' . $_SESSION['msg']['user_verification-error'] . '</div>';
		unset($_SESSION['msg']['user_verification-error']);
	}			
	if(isset($_SESSION['msg']['user_verification-success'])){
		echo '<div class="alert alert-success" role="alert">' . $_SESSION['msg']['user_verification-success'] . '</div>';
		unset($_SESSION['msg']['user_verification-success']);
	}
	
?>