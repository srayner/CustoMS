<?php

	if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

	class Helper{

		public static function checkUsernameIsLongEnough($username){
			if(strlen($username) < 4 || strlen($username) > 18){
				return false;
			} else{
				return true;
			}
		}

		public static function checkEmailIsValid($email){
			return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $email);
		}

		public static function sendMail($from,$to,$subject,$body){
			$headers = '';
			$headers .= "From: $from\n";
			$headers .= "Reply-to: $from\n";
			$headers .= "Return-Path: $from\n";
			$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Date: " . date('r', time()) . "\n";
			$headers .= "Content-type: text/html; charset=\"UTF-8\"; format=flowed \r\n";
			mail($to,$subject,$body,$headers);
		}

		public static function checkPasswordIsLongEnough($password){
			return strlen($password) >= 8 ? true : false;
		}

		public static function passwordsMatch($password1, $password2){
			return $password1 == $password2;
		}
	}

?>