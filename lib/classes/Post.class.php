<?php

    if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

    require_once 'Database.class.php';
    
    class Post{
        
        private $db;
        
		public function doCreatePost(){

			$this->db 	  		= new Database();
            $this->db 	  		= $this->db->connect();

            $error 		  		= array();

            $username 	  		= $_SESSION['username'];
			$role		  		= $_SESSION['role'];
			$email 		  		= $_SESSION['email'];

			$title		  		= $_POST['title'];
			$post 				= $_POST['post'];
			$image		  		= $_POST['image'];
			$category 			= $_POST['category'];

			$year_dir 			= "uploads/" . date("Y");
			$month_dir 			= $year_dir . "/" . date("m");
			$target_image_file 	= "";

			!file_exists($year_dir) && mkdir($year_dir);
			!file_exists($month_dir) && mkdir($month_dir);

			if(empty($title)){
				$error[] = 'The post must have a title.';
			}
			if(empty($post)){
				$error[] = 'The post must have some content.';
			}

			if(!empty($_FILES['image']['name'])){
				$image_name = basename($_FILES['image']['name']);
				$target_image_file = $month_dir . "/" . $image_name;
				$image_file_type = pathinfo($target_image_file, PATHINFO_EXTENSION);

				#Set upload file size to 3MB, set in bytes
				if($_FILES['image']['size'] > 3000000){
					$error[] = 'Sorry, your image is too large.';
				} else{
					$check_image_is_real = getimagesize($_FILES['image']['tmp_name']);
					if($check_image_is_real !== false){} else{
						$error[] = 'The file you uploaded was not an image.';
					}
					if(file_exists($target_image_file)){
						$error[] = 'Sorry, this image already exists. Try changing the file name.';
					}
					
					if($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png" && $image_file_type != "gif"){
						$error[] = 'The file type that you attempted to upload was not of an acceptable file type.';
					}

					if(move_uploaded_file($_FILES['image']['tmp_name'], $target_image_file)){
						// no error
					} else{
						$error[] = 'There was an error uploading your image.';
					}
				}

			}
			
			if(count($error) == 0){
				$published = 0;
				if($role == 'editor' || $role == 'admin'){
					$published = 1;
				}

				$insert_new_post = $this->db->prepare("INSERT INTO posts(username, email, title, post, image, published, date_post_created) VALUES(?, ?, ?, ?, ?, ?, NOW())");		
				$insert_new_post->bindParam(1, $username);
				$insert_new_post->bindParam(2, $email);
				$insert_new_post->bindParam(3, $title);
				$insert_new_post->bindParam(4, $post);
				$insert_new_post->bindParam(5, $target_image_file);
				$insert_new_post->bindParam(6, $published);
				$insert_new_post->execute();
				$new_post_insertion_result = $insert_new_post->rowCount();
				$post_id = $this->db->lastInsertId();

				$insert_category_for_post = $this->db->prepare("INSERT INTO categories(post_id, category) VALUES(?, ?)");
				$insert_category_for_post->bindParam(1, $post_id);
				$insert_category_for_post->bindParam(2, $category);
				$insert_category_for_post->execute();
				

				if($role == 'journalist'){
					if($new_post_insertion_result == 1){
					    $_SESSION['msg']['post_created-success'] = 'Your post has been created and is awaiting moderation by an editor!';
					} else{
						$error[] = 'The post could not be created..';
					}
				} else{
					if($new_post_insertion_result == 1){  
						header("Location: index.php?action=view_post&post_id=$post_id");
					} else{
						$error[] = 'The post could not be created..';
					}	
				}

			}
			
			if(count($error) != 0){
				$_SESSION['msg']['post_created-error'] = implode('<br />', $error);
			}

		}

		public function doGetPostsForApproval(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

			$query_get_all_non_published_posts_for_approval = $this->db->prepare("SELECT P.post_id, P.username, P.email, P.title, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.post_id = C.post_id AND P.published = 0");
            $query_get_all_non_published_posts_for_approval->execute();

			while($query_get_all_non_published_posts_for_approval_results = $query_get_all_non_published_posts_for_approval->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_all_non_published_posts_for_approval_results;
			}

			$_SESSION['posts_for_approval_results'] = $results;

		}

		public function doPostApproval(){
			
			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $error 		= array();

            $this->db->beginTransaction();

			$post_ids_selected_for_approval = $_POST['post_ids_selected_for_approval'];

			if(empty($post_ids_selected_for_approval)){
				$error[] = 'No posts were selected for approval..';
			} else{
				try{
					foreach($post_ids_selected_for_approval AS $post_id){
						$approve_post = $this->db->prepare("UPDATE posts SET published = 1 WHERE post_id = ?");
	            		$approve_post->bindParam(1, $post_id);
	            		$approve_post->execute();
					}
					$this->db->commit();
					$_SESSION['msg']['posts_for_approval-success'] = 'The selected posts were approved and published! Please wait for page to refresh.';
					header('Refresh: 2');
				} catch(PDOException $e){
	            	$this->db->rollback();
	            	$error[] = 'The selected posts were not able to be approved and published.';
	            	exit;
	            }
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['posts_for_approval-error'] = implode('<br />', $error);
			}

		}

		public function doGetFiveMostRecentPostsForViewing(){
			
			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

            $error  	= array();

            $query_five_most_recent_published_posts = $this->db->prepare("SELECT P.post_id, P.username, P.email, P.title, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.published = 1 AND P.post_id = C.post_id ORDER BY date_post_last_modified DESC LIMIT 5");
            $query_five_most_recent_published_posts->execute();

			while($query_five_most_recent_published_posts_results = $query_five_most_recent_published_posts->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_five_most_recent_published_posts_results;
			}

			if(count($results) == 0){
				$error[] = 'There are no posts to view.';
				$_SESSION['top_five_posts_for_viewing_results'] = null;
			} else{
				$_SESSION['top_five_posts_for_viewing_results'] = $results;
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['top_five_posts_for_viewing_results-error'] = implode('<br />', $error);
			}

		}

		public function doCreateComment(){

			$this->db 	  		= new Database();
            $this->db 	  		= $this->db->connect();

            $error 		  		= array();

			$title		  		= $_POST['title'];
			$name 				= $_POST['name'];
			$comment		  	= $_POST['comment'];
			$url		  		= $_POST['url'];
			$role 				= $_SESSION['role'];
			$post_id		  	= $_GET['post_id'];
			$ip 				= $_SERVER['REMOTE_ADDR'];

			if(empty($title)){
				$error[] = 'The comment must have a title.';
			}
			if(empty($comment)){
				$error[] = 'The comment must have some content.';
			}
			if(empty($name)){
				$error[] = 'We need your name.';
			}
			
			if(count($error) == 0){

				$approved = 0;
				if($role == 'editor' || $role == 'admin'){
					$approved = 1;
				}

				$insert_new_comment = $this->db->prepare("INSERT INTO comments(post_id, name, url, comment_title, comment, approved, ip, date_comment_created) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
				$insert_new_comment->bindParam(1, $post_id);
				$insert_new_comment->bindParam(2, $name);
				$insert_new_comment->bindParam(3, $url);
				$insert_new_comment->bindParam(4, $title);
				$insert_new_comment->bindParam(5, $comment);
				$insert_new_comment->bindParam(6, $approved);
				$insert_new_comment->bindParam(7, $ip);
				$insert_new_comment->execute();

				$insert_new_comment_result = $insert_new_comment->rowCount();
					
				if($insert_new_comment_result != 1){
					$error[] = 'Your comment could not be created..';
				} else{
					if($role == 'editor' || $role == 'admin'){
						$_SESSION['msg']['create_comment-success'] = 'Your comment has been created.';
						header("Refresh: 2");
					} else{
						$_SESSION['msg']['create_comment-success'] = 'Your comment has been created and is awaiting moderation.';
					}
				}

			}
			
			if(count($error) != 0){
				$_SESSION['msg']['comment_created-error'] = implode('<br />', $error);
			}

		}

		public function doGetComments(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $error 		= array();

            $result 	= array();

			$post_id 	= $_GET['post_id'];
			
			$query_get_approved_comments_details = $this->db->prepare("SELECT post_id, comment_id, name, url, comment_title, comment, approved, date_comment_created FROM comments WHERE  post_id = ? AND approved = 1 ORDER BY date_comment_created DESC");
			$query_get_approved_comments_details->bindParam(1, $post_id);
			$query_get_approved_comments_details->execute();
								
			while($query_get_approved_comments_details_results = $query_get_approved_comments_details->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_approved_comments_details_results;
			}

			$_SESSION['approved_comment_details'] = $results;

		}

		public function doGetCommentsForApproval(){

			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $results 	= array();

			$query_get_non_approved_comments_details = $this->db->prepare("SELECT P.post_id, P.title, Co.comment_id, Co.name, Co.url, Co.comment_title , Co.comment, Co.approved, Co.date_comment_created FROM posts P, comments Co WHERE P.post_id = Co.post_id AND Co.approved = 0");
            $query_get_non_approved_comments_details->execute();

			while($query_get_non_approved_comments_details_results = $query_get_non_approved_comments_details->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_non_approved_comments_details_results;
			}

			$_SESSION['comments_for_approval_results'] = $results;

		}

		public function doCommentApproval(){
			
			$this->db 	= new Database();
            $this->db 	= $this->db->connect();

            $error 		= array();

            $this->db->beginTransaction();

			$comments_selected_for_approval = $_POST['comments_selected_for_approval'];

			if(empty($comments_selected_for_approval)){
				$error[] = 'No comments were selected for approval..';
			} else{
				try{
					foreach($comments_selected_for_approval AS $commentId){
						$approve_comment = $this->db->prepare("UPDATE comments SET approved = 1 WHERE comment_id = ?");
	            		$approve_comment->bindParam(1, $commentId);
	            		$approve_comment->execute();
					}
					$this->db->commit();
					$_SESSION['msg']['comments_for_approval-success'] = 'The selected comments were approved and published! Please wait for page to refresh.';
					header('Refresh: 2');
				} catch(PDOException $e){
	            	$this->db->rollback();
	            	$error[] = 'The selected comments were not able to be approved..';
	            	exit;
	            }
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['comments_for_approval-error'] = implode('<br />', $error);
			}

		}

		public function doGetPostDetailsForEdit(){

			$this->db 				= new Database();
            $this->db 				= $this->db->connect();

            $error 					= array();

			$post_id 				= $_GET['post_id'];
			$_SESSION['prev_url'] 	= $_GET['prev'];
			
			if(ctype_digit($post_id)){
				$query_get_post_details_for_edit = $this->db->prepare("SELECT P.post_id, P.username, P.email, P.title, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.post_id = ? AND P.post_id = C.post_id");
				$query_get_post_details_for_edit->bindParam(1, $post_id);
				$query_get_post_details_for_edit->execute();
								
				$results = $query_get_post_details_for_edit->fetch(PDO::FETCH_ASSOC);

				if($results != null){
				    $_SESSION['post_details_for_edit'] = $results;
				} else {
					$_SESSION['post_details_for_edit'] = null;
					$error[] = 'The post id supplied in the query string is invalid..';
				}

			} else {
				$error[] = 'The post id supplied in the query string is invalid..';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['get_post_details_for_edit-error'] = implode('<br />', $error);
			} 

		}

		public function doUpdatePost(){

			$this->db 	  			= new Database();
            $this->db 	 			= $this->db->connect();

            $success 				= array();
            $error 					= array();

            $title 					= $_POST['title'];
            $post 					= $_POST['post'];
            $category 				= $_POST['category'];
			$post_id 				= $_GET ['post_id'];
			$image		  			= $_POST['image'];
			
			$target_image_file		= "";
			$year_dir 				= "uploads/" . date("Y");
			$month_dir 				= $year_dir . "/" . date("m");

			!file_exists($year_dir) && mkdir($year_dir);
			!file_exists($month_dir) && mkdir($month_dir);

			if(empty($title)){
				$error[] = 'The post must have a title.';
			}
			if(empty($post)){
				$error[] = 'The post must have some content.';
			}

			if(!empty($_FILES['image']['name'])){
				$image_name = basename($_FILES['image']['name']);
				$target_image_file = $month_dir . "/" . $image_name;
				$image_file_type = pathinfo($target_image_file, PATHINFO_EXTENSION);

				#Set upload file size to 3MB, set in bytes
				if($_FILES['image']['size'] > 3000000){
					$error[] = 'Sorry, your image is too large.';
				} else{
					$check_image_is_real = getimagesize($_FILES['image']['tmp_name']);
					if($check_image_is_real !== false){} else{
						$error[] = 'The file you uploaded was not an image.';
					}
					if(file_exists($target_image_file)){
						$error[] = 'Sorry, this image already exists. Try changing the file name.';
					}
					
					if($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png" && $image_file_type != "gif"){
						$error[] = 'The file type that you attempted to upload was not of an acceptable file type.';
					}

					if(move_uploaded_file($_FILES['image']['tmp_name'], $target_image_file)){
						if(isset($_SESSION['post_details_for_edit']['image']) && $_SESSION['post_details_for_edit']['image'] != ""){
							unlink($_SESSION['post_details_for_edit']['image']);
						}else{
							// no previous image
						}
					} else{
						$error[] = 'There was an error uploading your image.';
					}
				}

			}

			$update_post_details = $this->db->prepare("UPDATE posts P, categories C SET P.title = ?, P.post = ?, P.image = ?,  C.category = ? WHERE P.post_id = ? AND P.post_id = C.post_id");
			$update_post_details->bindParam(1, $title);
			$update_post_details->bindParam(2, $post);
			$update_post_details->bindParam(3, $target_image_file);
			$update_post_details->bindParam(4, $category);
			$update_post_details->bindParam(5, $post_id);
			$update_post_details->execute();

			$post_update_details_result = $update_post_details->rowCount();

			if($post_update_details_result == 1){
			    $success[] = "Post successfully updated.";

			    $last_param = "";
			    $last_param_exists = false;
			    $has_own_header = false;
			    if(count(explode('&', $_SERVER['QUERY_STRING'])) >= 4){
			    	if(isset($_GET['username'])){
			    		$last_param = "username=" . $_GET['username'];
			    		$last_param_exists = true;
			    	} else if(isset($_GET['keyword'])){
			    		### after changing title of post, search page will be loaded with no results or just that post that was edited absent from results
			    		$last_param = "keyword=" . $_GET['keyword'];
			    		$last_param_exists = true;
			    	} else if(isset($_GET['category'])){
			    	 	### after changing category of post, category page will be loaded with no results or just that post that was edited absent from results
			    	 	$last_param = "category=" . $_GET['category'];
			    	 	$last_param_exists = true;
			    	} else if(isset($_GET['view']) && $_GET['view'] == 'individual'){
			    	 	$post_id = (int)$_GET['post_id'];
			    	 	$has_own_header = true;
			    	 	header("Location: index.php?action=view_post&post_id=$post_id");
			    	}
			    }

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
				$error[] = 'The post was not updated..';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['post_update-error'] = implode('<br />', $error);
			}

		}

		public function doGetPostTitleForDeletion(){

			$this->db 	  			= new Database();
            $this->db 	  			= $this->db->connect();

            $success 				= array();
            $error 					= array();

			$post_id 				= $_GET['post_id'];
			$_SESSION['prev_url'] 	= $_GET['prev'];
			
			$query_get_post_title_for_deletion = $this->db->prepare("SELECT title, date_post_created FROM posts WHERE post_id = ?");
			$query_get_post_title_for_deletion->bindParam(1, $post_id);
			$query_get_post_title_for_deletion->execute();

			$query_get_post_title_for_deletion_result = $query_get_post_title_for_deletion->fetch();
				
			if($query_get_post_title_for_deletion_result != null){
			    $_SESSION['post_title_for_deletion'] = $query_get_post_title_for_deletion_result;
			} else{
				$_SESSION['post_title_for_deletion'] = null;
				$error[] = "Invalid post id..";
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['get_post_title_for_deletion-error'] = implode('<br />', $error);
			}

		}

		public function doDeletePost(){

			$this->db 	  	= new Database();
            $this->db 	  	= $this->db->connect();

            $success 		= array();
            $error 			= array();

			$post_id 		= $_GET['post_id'];
			
			$query_get_post_image_path = $this->db->prepare("SELECT image FROM posts WHERE post_id = ?");
			$query_get_post_image_path->bindParam(1, $post_id);
			$query_get_post_image_path->execute();

			$query_get_post_image_path_result = $query_get_post_image_path->fetch(PDO::FETCH_ASSOC);

			$delete_post = $this->db->prepare("DELETE FROM posts WHERE post_id = ?");
			$delete_post->bindParam(1, $post_id);
			$delete_post->execute();

			$delete_post_result = $delete_post->rowCount();
			
			$delete_category = $this->db->prepare("DELETE FROM categories WHERE post_id = ?");
			$delete_category->bindParam(1, $post_id);
			$delete_category->execute();

			$delete_category_result = $delete_category->rowCount();

			if($delete_post_result == 1 && $delete_category_result == 1 && $query_get_post_image_path_result != null){
			    $success[] = "Post successfully deleted.";
			    if($query_get_post_image_path_result['image'] != ""){
			    	unlink($query_get_post_image_path_result['image']);
			    }

			    $last_param = "";
			    $last_param_exists = false;
			    $has_own_header = false;
			    if(count(explode('&', $_SERVER['QUERY_STRING'])) >= 4){
			    	if(isset($_GET['username'])){
			    		$last_param = "username=" . $_GET['username'];
			    		$last_param_exists = true;
			    	} else if(isset($_GET['keyword'])){
			    		### after deleting post, search page will be loaded with no results or just that post that was deleted absent from results
			    		$last_param = "keyword=" . $_GET['keyword'];
			    		$last_param_exists = true;
			    	} else if(isset($_GET['category'])){
			    	 	$last_param = "category=" . $_GET['category'];
			    	 	$last_param_exists = true;
			    	} else if(isset($_GET['view']) && $_GET['view'] == 'individual'){
			    	 	$has_own_header = true;
			    	 	header("Location: index.php");
			    	}
			    }

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
				$error[] = 'The post was not deleted..';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['post_deletetion-error'] = implode('<br />', $error);
			} else if(count($success) != 0){
				$_SESSION['msg']['post_deletetion-success'] = implode('<br />', $success);
			}

		}

		public function doGetCommentTitleForDeletion(){

			$this->db 	  			= new Database();
            $this->db 	  			= $this->db->connect();

            $success 				= array();
            $error 					= array();

			$comment_id 			= $_GET['comment_id'];
			$_SESSION['prev_url'] 	= $_GET['prev'];
			
			$query_get_comment_title = $this->db->prepare("SELECT comment_title, date_comment_created FROM comments WHERE comment_id = ?");
			$query_get_comment_title->bindParam(1, $comment_id);
			$query_get_comment_title->execute();

			$query_get_comment_title_result = $query_get_comment_title->fetch();
				
			if($query_get_comment_title_result != null){
			    $_SESSION['comment_title_for_deletion'] = $query_get_comment_title_result;
			} else{
				$error[] = "Invalid comment id..";
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['get_comment_title_for_deletion-error'] = implode('<br />', $error);
			}

		}

		public function doDeleteComment(){

			$this->db 	  	= new Database();
            $this->db 	  	= $this->db->connect();

            $success 		= array();
            $error 			= array();

			$comment_id 	= $_GET['comment_id'];

			$delete_comment = $this->db->prepare("DELETE FROM comments WHERE comment_id = ?");
			$delete_comment->bindParam(1, $comment_id);
			$delete_comment->execute();

			$delete_comment_result = $delete_comment->rowCount();

			if($delete_comment_result != 0){
			    $success[] = "Comment successfully deleted!";

			    $last_param = "";
			    $last_param_exists = false;
			    $has_own_header = false;
			    if(count(explode('&', $_SERVER['QUERY_STRING'])) >= 4){
			    	if(isset($_GET['view']) && $_GET['view'] == 'individual'){
			    	 	$post_id = (int)$_GET['post_id'];
			    	 	$has_own_header = true;
			    	 	header("Location: index.php?action=view_post&post_id=$post_id");
			    	}
			    }

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
				$error[] = 'The comment was not deleted.';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['comment_deletetion-error'] = implode('<br />', $error);
			} else if(count($success) != 0){
				$_SESSION['msg']['comment_deletetion-success'] = implode('<br />', $success);
			}

		}

		public function doGetIndividualPost(){

			$this->db 	  			= new Database();
            $this->db 	  			= $this->db->connect();

            $error 					= array();
            $category_results 		= array();

			$post_id 				= $_GET ['post_id'];

			if(ctype_digit($post_id)){
				$query_get_individual_post_details = $this->db->prepare("SELECT P.post_id, P.username, P.email, P.title, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.post_id = ? AND P.published = 1 AND P.post_id = C.post_id");
				$query_get_individual_post_details->bindParam(1, $post_id);
				$query_get_individual_post_details->execute();
								
				$query_get_individual_post_details_results = $query_get_individual_post_details->fetch(PDO::FETCH_ASSOC);
				
				if($query_get_individual_post_details_results != null){
				    $_SESSION['individual_posts_for_viewing_results'] = $query_get_individual_post_details_results;
				} else {
					$_SESSION['individual_posts_for_viewing_results'] = null;
					$error[] = 'This post does not exist..';
				}
				
			} else {
				$error[] = 'The post id supplied in the query string is invalid..';
			}
			
			if(count($error) != 0){
				$_SESSION['msg']['individual_posts_viewing-error'] = implode('<br />', $error);
			}

			$_SESSION['page_before'] = $_SERVER['REQUEST_URI'];
		}

		public function doGetPostsByUsername(){

			$this->db 		= new Database();
            $this->db 		= $this->db->connect();

            $results 		= array();
            $error   		= array();

            $username 		= $_GET['username'];

			$query_get_post_details_for_published_posts_by_username = $this->db->prepare("SELECT P.post_id, P.title, P.email, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.post_id = C.post_id AND P.username = ? AND P.published = 1");
            $query_get_post_details_for_published_posts_by_username->bindParam(1, $username);
            $query_get_post_details_for_published_posts_by_username->execute();

            while($query_get_post_details_for_published_posts_by_username_result = $query_get_post_details_for_published_posts_by_username->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_post_details_for_published_posts_by_username_result;
			}

			if(count($results) == 0){
				$error[] = "There are no posts to view.";
				$_SESSION['posts_for_viewing_by_username_results'] = null;
				$_SESSION['current_posts_username'] = null;
			} else{
				$_SESSION['posts_for_viewing_by_username_results'] = $results;
				$_SESSION['current_posts_username'] = $username;
			}

			if(count($error) != 0){
				$_SESSION['msg']['posts_viewing_by_username-error'] = implode('<br />', $error);
			}

		}

            function doGetPostsByCategory() {

                $this->db 	= new Database();
                $this->db 	= $this->db->connect();

                $error   	= array();
           
                $results	= array();

                $category 	= $_GET['category'];

                $query_get_post_details_for_published_posts_by_category = $this->db->prepare("SELECT P.post_id, P.title, P.username, P.email, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id FROM posts P, categories C WHERE P.post_id = C.post_id AND C.category = ? AND P.published = 1");
                $query_get_post_details_for_published_posts_by_category->bindParam(1, $category);
                $query_get_post_details_for_published_posts_by_category->execute();

                while($query_get_post_details_for_published_posts_by_category_results = $query_get_post_details_for_published_posts_by_category->fetch(PDO::FETCH_ASSOC)){
                    $results[] = $query_get_post_details_for_published_posts_by_category_results;
                }
            
                $_SESSION['posts_for_viewing_by_categories_results'] = $results;
                $_SESSION['current_category'] = $category;
                
                if(count($results) == 0) {
                    $error[] = "There are no posts to view for this category.";
                }    
                
                if(count($error) != 0){
                    $_SESSION['msg']['posts_viewing_by_category-error'] = implode('<br />', $error);
                }
            }

		function doGetPostsByKeyword(){

			$this->db 			= new Database();
            $this->db 			= $this->db->connect();

            $error   			= array();
			$results 			= array();

            $keyword 			= $_GET['keyword'];
            $wildcard_keyword 	= '%' . $keyword . '%';

            $query_get_published_post_details_by_keyword = $this->db->prepare("SELECT P.post_id, P.username, P.email, P.title, P.post, P.image, P.date_post_created, P.date_post_last_modified, C.post_id, C.category FROM posts P, categories C WHERE P.post_id = C.post_id AND P.published = 1 AND (P.username LIKE ? OR P.email LIKE ? OR P.title LIKE ? OR P.post LIKE ?)");
            $query_get_published_post_details_by_keyword->bindParam(1, $wildcard_keyword);
            $query_get_published_post_details_by_keyword->bindParam(2, $wildcard_keyword);
            $query_get_published_post_details_by_keyword->bindParam(3, $wildcard_keyword);
            $query_get_published_post_details_by_keyword->bindParam(4, $wildcard_keyword);
            $query_get_published_post_details_by_keyword->execute();

            while($query_get_published_post_details_by_keyword_results = $query_get_published_post_details_by_keyword->fetch(PDO::FETCH_ASSOC)){
				$results[] = $query_get_published_post_details_by_keyword_results;
			}

			if(count($results) == 0){
				$error[] = "There are no posts to view for this search.";
				$_SESSION['posts_for_viewing_by_keyword_results'] = null;
			} else{
				$_SESSION['posts_for_viewing_by_keyword_results'] = $results;
			}

			$_SESSION['user_requested_keyword'] = $keyword;

			if(count($error) != 0){
				$_SESSION['msg']['posts_viewing_by_keyword-error'] = implode('<br />', $error);
			}

		}

    }

?>