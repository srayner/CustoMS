<?php

    if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

    class Constants {
        
        public static function defineConst(){
            # ROOT is defined in including file
            $pass_salt = file_get_contents('' . ROOT . '/../lib/salts/pass_salt.txt');
            define("PASS_SALT", $pass_salt);
            $pass_change_token_salt = file_get_contents('' . ROOT . '/../lib/salts/pass_change_token_salt.txt');
            define("PASS_CHANGE_TOKEN_SALT", $pass_change_token_salt);
            $cookie_salt = file_get_contents('' . ROOT . '/../lib/salts/cookie_salt.txt');
            define("COOKIE_SALT", $cookie_salt);
            define("TIME_LIMIT", 1800);
            define("SYS_URL", 'http://' . $_SERVER[HTTP_HOST]);
            define("SYSTEM_EMAIL", "ashleymenhennett@gmail.com");
            define("ADMIN_EMAIL", "ashleymenhennett@gmail.com");

       }
       
    }
    
?>