<?php

	ini_set('session.cookie_httponly', true);
	
	define("INCLUDE_CHECK", true);
	
	session_start();

	/* ROOT constant has to be defined here, as it is used before Constants.class.php is required and it's method: defineConst() is invoked */
	define("ROOT", $_SERVER['DOCUMENT_ROOT']);

	require ROOT . '/../lib/classes/Constants.class.php';
	require ROOT . '/../lib/classes/User.class.php';
	require ROOT . '/../lib/classes/Post.class.php';	
	require ROOT . '/../lib/classes/Security.class.php';
	require ROOT . '/../lib/classes/System.class.php';
	
	Constants::defineConst();
	Security::preventSessionHijacking();
    Security::checkTime();
	
	$user 	= new User();
	$post 	= new Post();
	$system = new System();
	
	/* User functionality */
	if(isset($_GET['action']) && $_GET['action'] == 'admin_sign_up'){
		$user->checkIfAdministrativeAccountExists();
		if(isset($_SESSION['admin_account_exists']) && !$_SESSION['admin_account_exists']){
			if(!isset($_SESSION['already_init'])){
				$system->init();
				$_SESSION['already_init'] = true;
			}
		}
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Admin Sign up'){
		$user->doAdministrativeAccountSignUp();
	}

	if(isset($_GET['action']) && $_GET['action'] == 'verify_account' && isset($_GET['token'])){
		$user->doVerifyAccount();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'log_out'){
		$user->doLogOut();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'show_dashboard'){
		$user->doGetUserDetailsForDashboard();
	}

	if(isset($_POST['submit']) && $_POST['submit'] == 'Sign up'){
		$user->doSignUp();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Log in'){
		$user->doLogIn();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Send Message'){
		$user->doSendContactEmail();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Request Password Change'){
		$user->doRequestPasswordChange();
	}
	if(isset($_POST['submit']) && $_POST['submit'] =='Change Password' && isset($_GET['token'])){
		$user->doPasswordChange();
	}
	if(isset($_POST['submit']) && $_POST['submit']== 'Delete My Account'){
		$user->doDeleteAccount();
	}

	if(isset($_GET['action']) && $_GET['action'] == 'approve_users' && $_SESSION['role'] == 'admin'){
		$user->doGetUsersForApproval();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Approve Users'){
		$user->doUserApproval();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['user_id']) && $_SESSION['role'] == 'admin'){
		$user->doGetUserForDeletion();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Delete user'){
		$user->doDeleteUser();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'show_users' && $_SESSION['role'] == 'admin'){
		$user->doGetUsersForAdmin();
	}
	
	/* Post functionality */
	if(isset($_POST['submit']) && ($_POST['submit'] == 'Submit for review' || $_POST['submit'] == 'Publish')){
		$post->doCreatePost();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'approve_posts' && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
		$post->doGetPostsForApproval();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Approve Posts'){
		$post->doPostApproval();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'view_post' && isset($_GET['post_id']) && isset($_POST['submit']) && $_POST['submit'] == 'Add Comment'){
		$post->doCreateComment();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'approve_comments' && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
		$post->doGetCommentsForApproval();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Approve Comments'){
		$post->doCommentApproval();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'edit_post' && isset($_GET['post_id'])){
		$post->doGetPostDetailsForEdit();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Update post'){
		$post->doUpdatePost();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'delete_post' && isset($_GET['post_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
		$post->doGetPostTitleForDeletion();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Delete post'){
		$post->doDeletePost();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'delete_comment' && isset($_GET['comment_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
		$post->doGetCommentTitleForDeletion();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Delete Comment'){
		$post->doDeleteComment();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'view_post' && isset($_GET['post_id'])){
		$post->doGetIndividualPost();
		$post->doGetComments();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'view_user_posts' && isset($_GET['username'])){
		$post->doGetPostsByUsername();
	}
	if(isset($_GET['action']) && $_GET['action'] == 'view_category_posts' && isset($_GET['category'])){
		$post->doGetPostsByCategory();
	}
	if(isset($_GET['keyword']) && isset($_GET['action']) && $_GET['action'] == 'view_search_posts'){
		$post->doGetPostsByKeyword();
	}
	if(!isset($_GET['action'])){
		$post->doGetFiveMostRecentPostsForViewing();
	}
	
	require('views/header.php');
	if(isset($_SESSION['user_id']) && isset($_COOKIE['user']) && $_COOKIE['user'] == hash_hmac('whirlpool', $_SESSION['username'].$_SESSION['user_id'], COOKIE_SALT)):
		#logged in
		if(isset($_GET['action']) && $_GET['action'] == 'delete_account' && $_SESSION['role'] != 'admin'){
			require('views/delete_account.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_account' && $_SESSION['role'] == 'admin'){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'create_post'){
			require('views/create_post.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_posts' && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
			require('views/posts_for_approval.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_posts' && ($_SESSION['role'] != 'admin' || $_SESSION['role'] != 'editor')){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_users' && $_SESSION['role'] == 'admin'){
			require('views/admin/users_for_approval.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_users' && $_SESSION['role'] != 'admin'){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_comments' && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
			require('views/comments_for_approval.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_comments' && ($_SESSION['role'] != 'admin' || $_SESSION['role'] != 'editor')){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'show_users' && $_SESSION['role'] == 'admin'){
			require('views/admin/show_users.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'show_users' && $_SESSION['role'] != 'admin'){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'edit_post' && isset($_GET['post_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
			require('views/edit_post.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'edit_post' && isset($_GET['post_id']) && ($_SESSION['role'] != 'admin' || $_SESSION['role'] != 'editor')){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_post' && isset($_GET['post_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
			require('views/delete_post.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_post' && isset($_GET['post_id']) && ($_SESSION['role'] != 'admin' || $_SESSION['role'] != 'editor')){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['user_id']) && $_SESSION['role'] == 'admin'){
			require('views/admin/delete_user.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['user_id']) && $_SESSION['role'] != 'admin'){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_comment' && isset($_GET['comment_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor')){
			require('views/delete_comment.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_comment' && isset($_GET['comment_id']) && ($_SESSION['role'] != 'admin' || $_SESSION['role'] != 'editor')){
			require('views/insufficient_privileges.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_post' && isset($_GET['post_id'])){
			require('views/individual_post_for_viewing.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_user_posts' && isset($_GET['username'])){
			require('views/posts_for_viewing_by_username.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_category_posts' && isset($_GET['category'])){
			require('views/posts_for_viewing_by_category.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_search_posts' && isset($_GET['keyword'])){
			require('views/posts_for_viewing_by_keyword.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'contact_us'){
			require('views/contact_us.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'request_password_change'){
			require('views/request_password_change.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'password_change' && isset($_GET['token'])){
			require('views/password_change.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'verify_account' && isset($_GET['token'])){
			require('views/verify_account.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'show_dashboard'){
			require('views/show_dashboard.php');
		} else{
			require('views/top_five_posts_for_viewing.php');
		}
	else:
		#not logged in
		if(isset($_GET['action']) && $_GET['action'] == 'sign_up'){
			require('views/sign_up.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'admin_sign_up'){
			require('views/admin/admin_sign_up.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'log_in'){
			require('views/log_in.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_account'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'create_post'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_posts'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'edit_post'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_post'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_users'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'approve_comments'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'show_users'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_post'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_user'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'delete_comment'){
			require('views/no_auth.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_post' && isset($_GET['post_id'])){
			require('views/individual_post_for_viewing.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_user_posts' && isset($_GET['username'])){
			require('views/posts_for_viewing_by_username.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_category_posts' && isset($_GET['category'])){
			require('views/posts_for_viewing_by_category.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'view_search_posts' && isset($_GET['keyword'])){
			require('views/posts_for_viewing_by_keyword.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'contact_us'){
			require('views/contact_us.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'request_password_change'){
			require('views/request_password_change.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'password_change' && isset($_GET['token'])){
			require('views/password_change.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'verify_account' && isset($_GET['token'])){
			require('views/verify_account.php');
		} else if(isset($_GET['action']) && $_GET['action'] == 'show_dashboard'){
			require('views/no_auth.php');
		} else{
			require('views/top_five_posts_for_viewing.php');
		}
	endif;
	require('views/footer.php');