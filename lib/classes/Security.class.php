<?php

    if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

    class Security {
        
        public static function preventSessionHijacking(){
            
            if(isset($_SESSION['last_ip']) == false){
                $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']){
                setcookie("user", "", time() - 3600);
                session_unset();
                session_destroy();
            }
        }
        
        public static function checkTime(){
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > TIME_LIMIT)) {
                setcookie("user", "", time() - 3600);
                session_unset();
                session_destroy();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }
        
    }
    
?>