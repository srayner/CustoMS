<?php

    if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

    require_once 'Database.class.php';
    require_once 'Helper.class.php';
    
    class User{
        
        private $db;
        
        public function doLogOut(){

            setcookie("user", "", time() - 3600);
            if(isset($_SESSION)){
            	session_unset();
            	session_destroy();
            }
            header("Location: index.php");
            exit();
            
        }

        public function doAdministrativeAccountSignUp(){

        	$this->db 			= new Database();
            $this->db 			= $this->db->connect();
            
			$error 				= array();
                
            $username 			= $_POST['username'];
            $email 				= $_POST['email'];
            $password 			= $_POST['password'];
            $password_repeat 	= $_POST['password_repeat'];
            $role 				= "admin";
            $verified 			= 1;
            $active 			= 1;
                
			if(!Helper::checkUsernameIsLongEnough($username)){
				$error[] = 'Your username must be between 4 and 18 characters!';
			}

			if(!Helper::checkEmailIsValid($email)){
				$error[] = 'Your email is not valid!';
			}

			$query_email_exists = $this->db->prepare("SELECT 1 FROM users WHERE email = ?");
            $query_email_exists->bindParam(1, $email);
            $query_email_exists->execute();
			$query_email_exists_result = $query_email_exists->rowCount();
						
			if($query_email_exists_result > 0){
				$error[] = 'Sorry, but that email has already been used!';
			}
		
            $query_username_exists = $this->db->prepare("SELECT 1 FROM users WHERE username = ?");
            $query_username_exists->bindParam(1, $username);
            $query_username_exists->execute();
			$query_username_exists_result = $query_username_exists->rowCount();
            
            if($query_username_exists_result > 0){
				$error[] = 'Sorry, but that username is taken!';
			} 
                
			if(!Helper::passwordsMatch($password, $password_repeat)){
				$error[] = 'Sorry, but your passwords do not match!';
			}
			
			if(!Helper::checkPasswordIsLongEnough($password)){
				$error[] = 'Passwords must be at least 8 characters long!';
			}
			
			if(count($error) == 0){			
				$hash = hash_hmac('whirlpool', $password, PASS_SALT);
				$ip = $_SERVER['REMOTE_ADDR'];
						
				$insert_new_user = $this->db->prepare("INSERT INTO users(username, email, hash, role, ip, verified, active, date_user_created) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
                $insert_new_user->bindParam(1, $username);
                $insert_new_user->bindParam(2, $email);
                $insert_new_user->bindParam(3, $hash);
                $insert_new_user->bindParam(4, $role);
                $insert_new_user->bindParam(5, $ip);
                $insert_new_user->bindParam(6, $verified);
                $insert_new_user->bindParam(7, $active);
				$insert_new_user->execute();
				$insert_new_user_result = $insert_new_user->rowCount();
		
				if($insert_new_user_result == 1){
					/* Helper::sendMail() Arguments: $from, $to, $subject, $body */
					Helper::sendMail(SYSTEM_EMAIL, $email, SYS_URL . ' - Your New Account', 'Hello ' . $username . '.<br />Your account has been created!<br />You have ' . $role . ' privileges.<br />Access this link to log in: <a href="' . SYS_URL . '/index.php?action=log_in">Log in</a>.<br />If the above link does not work, please copy and paste the following URL into the address bar of your browser to log in: ' . SYS_URL . '/index.php?action=log_in <br /><br />Regards,<br />' . SYS_URL . '.');
				    $_SESSION['msg']['sign_up_admin-success'] = 'We have sent you an email to confirm your new account! Please <a href="index.php?action=log_in">Log in</a>.';
				}
			}

			if(count($error) != 0){
				$_SESSION['msg']['sign_up_admin-error'] = implode('<br />',$error);
			}

        }
        
        public function checkIfAdministrativeAccountExists(){

        	$this->db 		= new Database();
            $this->db 		= $this->db->connect();

			$admin 			= "admin";
			$admin_exists 	= false;

			$query_admin_exists = $this->db->prepare("SELECT 1 FROM users WHERE role = ?");
            $query_admin_exists->bindParam(1, $admin);
            $query_admin_exists->execute();
			$query_admin_exists_result = $query_admin_exists->rowCount();
            
            if($query_admin_exists_result > 0){
				$admin_exists = true;
			} 

			$_SESSION['admin_account_exists'] = $admin_exists;

        }

        public function doSignUp(){
                
            $this->db 			= new Database();
            $this->db 			= $this->db->connect();
            
			$error 				= array();
                
            $username 			= $_POST['username'];
            $email 				= $_POST['email'];
            $password 			= $_POST['password'];
            $password_repeat 	= $_POST['password_repeat'];
            $role 				= $_POST['role'];
            $verified 			= 0;
            $active				= 0;
                
			if(!Helper::checkUsernameIsLongEnough($username)){
				$error[] = 'Your username must be between 4 and 18 characters!';
			}

			if(!Helper::checkEmailIsValid($email)){
				$error[] = 'Your email is not valid!';
			}

			$query_email_exists = $this->db->prepare("SELECT 1 FROM users WHERE email = ?");
            $query_email_exists->bindParam(1, $email);
            $query_email_exists->execute();
			$query_email_exists_result = $query_email_exists->rowCount();
						
			if($query_email_exists_result > 0){
				$error[] = 'Sorry, but that email has already been used!';
			}
		
            $query_username_exists = $this->db->prepare("SELECT 1 FROM users WHERE username = ?");
            $query_username_exists->bindParam(1, $username);
            $query_username_exists->execute();
			$query_username_exists_result = $query_username_exists->rowCount();
            
            if($query_username_exists_result > 0){
				$error[] = 'Sorry, but that username is taken!';
			} 
                
			if(!Helper::passwordsMatch($password, $password_repeat)){
				$error[] = 'Sorry, but your passwords do not match!';
			}
			
			if(!Helper::checkPasswordIsLongEnough($password)){
				$error[] = 'Passwords must be at least 8 characters long!';
			}
			
			if(count($error) == 0){			
				$hash = hash_hmac('whirlpool', $password, PASS_SALT);
				$ip = $_SERVER['REMOTE_ADDR'];
						
				$insert_new_user = $this->db->prepare("INSERT INTO users(username, email, hash, role, ip, verified, active, date_user_created) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
                $insert_new_user->bindParam(1, $username);
                $insert_new_user->bindParam(2, $email);
                $insert_new_user->bindParam(3, $hash);
                $insert_new_user->bindParam(4, $role);
                $insert_new_user->bindParam(5, $ip);
                $insert_new_user->bindParam(6, $verified);
                $insert_new_user->bindParam(7, $active);
				$insert_new_user->execute();
				$insert_new_user_result = $insert_new_user->rowCount();

				$token = md5($_SERVER['REMOTE_ADDR'].microtime().mt_rand());

				$insert_new_user_to_verify = $this->db->prepare("INSERT INTO users_to_be_verified(username, email, token, ip, date_account_verification_requested) VALUES(?, ?, ?, ?, NOW())");
                $insert_new_user_to_verify->bindParam(1, $username);
                $insert_new_user_to_verify->bindParam(2, $email);
                $insert_new_user_to_verify->bindParam(3, $token);
                $insert_new_user_to_verify->bindParam(4, $ip);
				$insert_new_user_to_verify->execute();
				$insert_new_user_to_verify_result = $insert_new_user_to_verify->rowCount();
		
				if($insert_new_user_result == 1 && $insert_new_user_to_verify_result == 1){
					/* Helper::sendMail() Arguments: $from, $to, $subject, $body */
					Helper::sendMail(SYSTEM_EMAIL, $email, SYS_URL . " - Please Verify Your Account", "Hello " . $username . ".<br />Your account has been created!<br />You have " . $role . " privileges.<br />Please verify your account here: <a href='" . SYS_URL . "/index.php?action=verify_account&token=$token'>Verify My Account</a>.<br />If the above link does not work, please copy and paste the following URL into the address bar of your browser to verify your account: " . SYS_URL . "/index.php?action=verify_account&token=$token<br /><br />Regards,<br />" . SYS_URL . ".");
				    $_SESSION['msg']['sign_up-success'] = 'We have sent you an email to confirm your new account!';
				}
			}

			if(count($error) != 0){
				$_SESSION['msg']['sign_up-error'] = implode('<br />',$error);
			}    
       	}
        
       	public function doVerifyAccount(){

       		$this->db 	= new Database();
       		$this->db 	= $this->db->connect();

       		$error 		= array();

       		$token 		= $_GET['token'];

       		$query_username_account_verify_request_token = $this->db->prepare("SELECT UV.username FROM users_to_be_verified UV WHERE UV.token = ?");
       		$query_username_account_verify_request_token->bindParam(1, $token);
       		$query_username_account_verify_request_token->execute();
       		$query_username_account_verify_request_token_results = $query_username_account_verify_request_token->fetch(PDO::FETCH_ASSOC);

       		if($query_username_account_verify_request_token_results != null){
       			if($query_username_account_verify_request_token_results['username'] != null){

       				$username = $query_username_account_verify_request_token_results['username'];
       				$_SESSION['verify_account_results']['username'] = $username;

       				$delete_from_users_to_be_verified = $this->db->prepare("DELETE FROM users_to_be_verified WHERE username = ?");
       				$delete_from_users_to_be_verified->bindParam(1, $username);
       				$delete_from_users_to_be_verified->execute();
       				$delete_from_users_to_be_verified_result = $delete_from_users_to_be_verified->rowCount();

       				$update_user_verify_field = $this->db->prepare("UPDATE users SET verified = 1 WHERE username = ?");
       				$update_user_verify_field->bindParam(1, $username);
       				$update_user_verify_field->execute();
       				$update_user_verify_field_result = $update_user_verify_field->rowCount();

       				if($delete_from_users_to_be_verified_result > 0 && $update_user_verify_field_result == 1){
       					$_SESSION['msg']['user_verification-success'] = "You have successfully verified your email address, $username!<br /><a href='index.php?action=log_in'>Log in</a>.";
       				} else{
       					$error[] = 'An error occurred. Please contact admin.';
       				}

       			}
       		} else{
       			$error[] = 'Invalid token.';
       		}

       		if(count($error) != 0){
				$_SESSION['msg']['user_verification-error'] = implode('<br />', $error);
       		}

       	}

       	public function doGetUsersForApproval(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

			$query_get_all_non_active_users_for_approval = $this->db->prepare("SELECT U.user_id, U.username, U.email, U.role, U.date_user_created FROM users U WHERE U.active = 0");
            $query_get_all_non_active_users_for_approval->execute();

			while($query_get_all_non_active_users_for_approval_results = $query_get_all_non_active_users_for_approval->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_all_non_active_users_for_approval_results;
			}

			$_SESSION['users_for_approval_results'] = $results;

		}

		public function doUserApproval(){
			
			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $error 		= array();

            $this->db->beginTransaction();

			$user_ids_selected_for_approval = $_POST['user_ids_selected_for_approval'];

			if(empty($user_ids_selected_for_approval)){
				$error[] = 'No users were selected for approval..';
			} else{
				try{
					foreach($user_ids_selected_for_approval AS $user_id){
						$approve_user = $this->db->prepare("UPDATE users SET active = 1 WHERE user_id = ?");
	            		$approve_user->bindParam(1, $user_id);
	            		$approve_user->execute();
					}
					$this->db->commit();
					$_SESSION['msg']['users_for_approval-success'] = 'The selected users were approved! Please wait for page to refresh.';
					header('Refresh: 2');
				} catch(PDOException $e){
	            	$this->db->rollback();
	            	$error[] = 'The selected users were not able to be approved.';
	            	exit;
	            }
			}

			if(count($error) != 0){
				$_SESSION['msg']['users_for_approval-error'] = implode('<br />', $error);
			}

		}

		public function doGetUserForDeletion(){

			$this->db 	  			= new Database();
            $this->db 	  			= $this->db->connect();

            $success 				= array();
            $error 					= array();

			$user_id 				= $_GET['user_id'];
			$_SESSION['prev_url'] 	= $_GET['prev'];
			
			$query_get_user_for_deletion = $this->db->prepare("SELECT U.username, U.date_user_created FROM users U WHERE U.user_id = ?");
			$query_get_user_for_deletion->bindParam(1, $user_id);
			$query_get_user_for_deletion->execute();

			$query_get_user_for_deletion_result = $query_get_user_for_deletion->fetch();
				
			if($query_get_user_for_deletion_result != null){
			    $_SESSION['user_for_deletion'] = $query_get_user_for_deletion_result;
			} else{
				$_SESSION['user_for_deletion'] = null;
				$error[] = "Invalid user id..";
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['get_user_username_for_deletion-error'] = implode('<br />', $error);
			}

		}

		public function doDeleteUser(){

			$this->db 	  	= new Database();
            $this->db 	  	= $this->db->connect();

            $success 		= array();
            $error 			= array();

			$user_id 		= $_GET['user_id'];

			$delete_user = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
			$delete_user->bindParam(1, $user_id);
			$delete_user->execute();

			$delete_user_result = $delete_user->rowCount();

			if($delete_user_result == 1){
			    $success[] = "User successfully deleted.";

			    $last_param = "";
			    $last_param_exists = false;
			    $has_own_header = false;

			    if(!$has_own_header){
			    	if(isset($_SESSION['prev_url'])){
				    	$prev_url = $_SESSION['prev_url'];
				    	if($last_param_exists){
				    		$prev_url .= "&";
				    		$prev_url .= $last_param;
				    	}
				    	header("Location: $prev_url");
			    	}
			    }

			} else{
				$error[] = 'The user was not deleted..';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['user_deletetion-error'] = implode('<br />', $error);
			} else if(count($success) != 0){
				$_SESSION['msg']['user_deletetion-success'] = implode('<br />', $success);
			}

		}

		public function doGetUsersForAdmin(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

            $admin 		= "admin";

			$query_get_all_users = $this->db->prepare("SELECT U.user_id, U.username, U.email, U.role, U.date_user_created FROM users U WHERE U.role != ?");
            $query_get_all_users->bindParam(1, $admin);
            $query_get_all_users->execute();

			while($query_get_all_users_results = $query_get_all_users->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_all_users_results;
			}

			$_SESSION['get_all_users_results'] = $results;

		}

		public function doGetUserDetailsForDashboard(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

            $user_id 	= $_SESSION['user_id'];

          	$query_get_current_users_details = $this->db->prepare("SELECT U.user_id, U.username, U.email, U.role, U.date_user_created FROM users U WHERE U.user_id = ?");
	        $query_get_current_users_details->bindParam(1, $user_id);
	        $query_get_current_users_details->execute();

			while($query_get_current_users_details_results = $query_get_current_users_details->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_current_users_details_results;
			}

			$_SESSION['get_user_details_for_dashboard'] = $results;

		}

		# After login, the following session variables are available in this class and in the view templates:
        # $_SESSION['user_id']: the user's id as in database, $_SESSION['username']: the current user's username, $_SESSION['email']: the current user's email address, $_SESSION['role']: the current user's role within the application
        public function doLogIn(){

            $this->db 	= new Database();
	        $this->db 	= $this->db->connect();
            
			$error 		= array();
                
			$email 		= $_POST['email'];
			$password 	= $_POST['password'];
                
			if(empty($email) || empty($password)){
				$error[] = 'All the fields are required!';
			}
		
			if(count($error) == 0){
                $hash = hash_hmac('whirlpool', $password, PASS_SALT);
				$query_user_details = $this->db->prepare("SELECT U.user_id, U.username, U.role FROM users U WHERE U.email = ? AND U.hash = ? AND U.verified = 1 AND U.active = 1");
				$query_user_details->bindParam(1, $email);
                $query_user_details->bindParam(2, $hash);
                $query_user_details->execute();
				$query_user_details_result = $query_user_details->fetch(PDO::FETCH_ASSOC);

				if($query_user_details_result['username']){
					$_SESSION['user_id']    = $query_user_details_result['user_id'];
					$_SESSION['username']   = $query_user_details_result['username'];
					$_SESSION['email']      = $email;
					$_SESSION['role']   	= $query_user_details_result['role'];
					
					setcookie("user", hash_hmac('whirlpool', $_SESSION['username'].$_SESSION['user_id'], COOKIE_SALT));
					session_regenerate_id();
					header("Location: index.php");
				} else {
					$error[]= 'Incorrect Login Details!<br />Make sure you have verified your account via the confirmation email sent when you signed up for an account!<br />Administration will also have to activate your account, which could take some time. Please contact admin is you believe it is taking too long.';
				}
			}
		
			if(count($error) != 0){
				$_SESSION['msg']['log_in-err'] = implode('<br />', $error);
			}
        }
	
        function doSendContactEmail(){

			$error 		= array();
			$success 	= array();

			$name 		= $_POST['name'];
			$email	 	= $_POST['email'];
			$phone	 	= $_POST['phone'];
			$subject 	= $_POST['subject'];
			$message 	= $_POST['message'];


			if(empty($name) || empty($email) || empty($subject) || empty($message)){
				$error[] = 'All fields, except phone number are required.';
			}

			if(!EmailFunctions::checkEmailIsValid($email)){
				$error[]= 'Your email is not valid!';
			}

			if(count($error) == 0){
				/* Helper::sendMail() Arguments: $from, $to, $subject, $body */
				Helper::sendMail($contact_email, ADMIN_EMAIL, SYS_URL . ' -' . $contact_subject . ' message', 'Hello Admin, ' . $contact_name . ' has just sent you a message.<br />DETAILS:<br />NAME: ' . $contact_name . '<br />EMAIL: ' . $contact_email . '<br />Phone Number: ' . $contact_phone . '<br />Subject: ' . $contact_subject . '<br />Message: ' . $contact_message . '<br /><br />Regards,<br />' . SYS_URL);
				$success[] = 'Your message was successfully sent!';
			}

			if(count($error) != 0){
				$_SESSION['msg']['contact_us-error'] = implode('<br />', $error);
			} else{
				$_SESSION['msg']['contact_us-success'] = implode('<br />', $success);
			}

		}

		public function doRequestPasswordChange(){

            $this->db 	= new Database();
            $this->db 	= $this->db->connect();
            
			$error 		= array();
            $success 	= array();

            $email 		= $_POST['email'];
            $username 	= $_POST['username'];
            $ip 		= $_SERVER['REMOTE_ADDR'];

	    	
		    if(empty($email) || empty($username)){
		    	$error[] = 'All the fields are required!';
		    }
		
		    $query_email_exists = $this->db->prepare("SELECT U.email FROM users U WHERE U.email = ? AND U.username = ?");
		    $query_email_exists->bindParam(1, $email);
            $query_email_exists->bindParam(2, $username);
            $query_email_exists->execute();
	    	$query_email_exists_results = $query_email_exists->rowCount();

	    	if($query_email_exists_results == 1){		
				$query_token_request_count = $this->db->prepare("SELECT COUNT(username) FROM token_requests WHERE email = ? AND date_created BETWEEN NOW() - 60 * 60 * 24 * 31 AND NOW()");
				$query_token_request_count->bindParam(1, $email);
                $query_token_request_count->execute();
				$query_token_request_count_results = $query_token_request_count->fetch(PDO::FETCH_NUM);

				if(intval($query_token_request_count_results) > 4){
				    $error[] = 'You cannot request more than 4 tokens in 31 days!';
				} else{
			    	$insert_new_token_request = $this->db->prepare("INSERT INTO token_requests(username, email, date_created) VALUES(?, ?, NOW())");
			    	$insert_new_token_request->bindParam(1, $username);
	                $insert_new_token_request->bindParam(2, $email);
	                $insert_new_token_request->execute();            
	                              
	                $token = hash_hmac('whirlpool', $_SERVER['REMOTE_ADDR'].microtime().mt_rand(), PASS_CHANGE_TOKEN_SALT);
			    	
			    	$insert_new_token = $this->db->prepare("INSERT INTO tokens(email, token, date_created, ip) VALUES(?, ?, NOW(), ?)");
			   		$insert_new_token->bindParam(1, $email);
	                $insert_new_token->bindParam(2, $token);
	                $insert_new_token->bindParam(3, $ip);
	                $insert_new_token->execute();
			    	$insert_new_token_result = $insert_new_token->rowCount();

					if($insert_new_token_result != 1){
					    $error[] = 'We could not generate the token needed to change your password at this time..';
					} else{
		    			/* Helper::sendMail() Arguments: $from, $to, $subject, $body */
		    		    Helper::sendMail(ADMIN_EMAIL, $email, 'Your ' . SYS_URL . ' change password link', 'Hello ' . $username . ',<br />Your unique password change link has been generated.<br />Please click on the following link: <a href="' . SYS_URL . '/index.php?action=password_change&token=' . $token . '">password change link</a>.<br />If the link does not work, please copy and paste the following URL into the address bar of your browser to change your password: ' . SYS_URL . '/index.php?action=password_change&token=' . $token . '<br /><br />Regards,<br />' . SYS_URL . '.');		
		    		    $success[] = 'The unique link needed to change your password has been sent to the email address linked to your account.';
					} 
				}
				
			} else{
				$error[] = 'Incorrect email or username..';
		    }
		
		    if($error){
				$_SESSION['msg']['request_password_change-error'] = implode('<br />', $error);
		    } else{
				$_SESSION['msg']['request_password_change-success'] = implode('<br />', $success);
		    }
        }
        
        public function doPasswordChange(){

            $this->db 		= new Database();
            $this->db 		= $this->db->connect();

            $error 			= array();
            $success 		= array();

            $token 			= $_GET['token'];
            $email 			= $_POST['email'];
	    	$password 		= $_POST['password'];
            $password_repeat= $_POST['password_repeat'];
	    	
	    	# Deletes old unused tokens that were created before 1 hour ago
            $clear_old_tokens = $this->db->prepare("DELETE FROM tokens WHERE date_created < NOW() - INTERVAL 1 HOUR");
            $clear_old_tokens->execute();

            if(empty($password) || empty($password_repeat) || empty($email)){
				$error[] = 'All fields are required!';
	    	}
            
            if($password != $password_repeat){
				$error[] = 'Passwords don\'t match!';
	    	}

            if(count($error) == 0){
                $query_email_check_token_is_valid = $this->db->prepare("SELECT T.email FROM tokens T WHERE T.token LIKE ? ");
                $query_email_check_token_is_valid->bindParam(1, $token);
                $query_email_check_token_is_valid->execute();
                $query_email_check_token_is_valid = $query_email_check_token_is_valid->fetch(PDO::FETCH_ASSOC);
				$query_email_check_token_is_valid = $query_email_check_token_is_valid['email'];

	            if($email == $query_email_check_token_is_valid){
	                $hash = hash_hmac('whirlpool', $password, PASS_SALT);
	                $update_user_password_hash = $this->db->prepare("UPDATE users SET hash = ? WHERE email = ?");
	                $update_user_password_hash->bindParam(1, $hash);
	                $update_user_password_hash->bindParam(2, $email);
	                $update_user_password_hash->execute();
	                $update_user_password_hash_result = $update_user_password_hash->rowCount();

	                if($update_user_password_hash_result == 1){
	                    $success[] = 'Your password has been successfully changed. Please wait for the page to redirect.';
	                    /* Helper::sendMail() Arguments: $from, $to, $subject, $body */
	    		    	Helper::sendMail(ADMIN_EMAIL, $email, 'Your ' . SYS_URL . ' password has been changed', 'Hello,<br />Your password has been changed.<br />If you did not request or make this change, please click on the following link: <a href="' . SYS_URL . '/index.php?action=contact_us">contact us</a>.<br />If the link does not work, please copy and paste the following URL into the address bar of your browser to contact us: ' . SYS_URL . '/index.php?action=contact_us <br /><br />Regards,<br />' . SYS_URL . '.');		
	                    $clear_used_token = $this->db->prepare("DELETE FROM tokens WHERE email = ? AND token = ? ");
	                    $clear_used_token->bindParam(1, $email);
	                    $clear_used_token->bindParam(2, $token);
	                    $clear_used_token->execute();
	                    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != null){
	                    	$user_id = $_SESSION['user_id'];
	                    	header("Refresh: 2; url= index.php?action=show_dashboard&user_id=$user_id", true, 303);
	                    }else{	
	                    	header("Refresh: 2; url= index.php?action=log_in", true, 303);
	                    }
	                }else{
	                    $error[] = 'You cannot use the same password twice in a row..';
	                }
			    
	            }else{
	                $error[] = 'Sorry, but your email is incorrect.';
	            }
	        }

	        if(count($error)){
			    $_SESSION['msg']['password_change-error'] = implode('<br />', $error);
		   	}
	        if(count($success)){
			    $_SESSION['msg']['password_change-success'] = implode('<br />', $success);
		    }

		}

		public function doDeleteAccount(){

	    	$this->db 			= new Database();
            $this->db 			= $this->db->connect();
		
		    $error 				= array();
		    $success 			= array();		

		    $password 			= $_POST['password'];
		    $username 			= $_SESSION['username'];
		    $email 				= $_SESSION['email'];
			
		    if(empty($password)){
				$error[] = 'We need you to enter in your password to authenticate your action!';
		    }

		    if(!count($error)){
				$hash = hash_hmac('whirlpool', $password, PASS_SALT);
				$delete_account = $this->db->prepare("DELETE FROM users WHERE hash LIKE ? AND username = ?");
				$delete_account->bindParam(1, $hash);
				$delete_account->bindParam(2, $username);
				$delete_account->execute();
				$delete_account_result = $delete_account->rowCount();
				
				if($delete_account_result == 1){
				    /* Helper::sendMail() Arguments: $from, $to, $subject, $body */
					Helper::sendMail(SYSTEM_EMAIL, $email, SYS_URL . ' - Your account has been deleted!', 'Hello ' . $username . '.<br /> Your ' . SYS_URL . ' account has been successfully deleted!<br />Regards,<br />' . SYS_URL . '.');
				    setcookie("user", "", time() - 3600);
				    session_unset();
				    session_destroy();
				    session_start();
				    $_SESSION['msg']['delete_account-success'] = 'Success, you will receive an email confirming the deletion of your '. SYS_URL .' account!';
				    header("Location: index.php");
				} else{
				    $error[] = 'Incorrect details!';
				}
		    }
		
		    if(count($error)){
				$_SESSION['msg']['delete_account-error'] = implode('<br />', $error);
		    }

		}

    }

?>