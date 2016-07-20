<?php

    if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');

    class System {
        
        public function init(){
            
            define("ROOT", $_SERVER['DOCUMENT_ROOT']);

            $file      = ROOT . '/../lib/salts/pass_salt.txt';
            $generator = openssl_random_pseudo_bytes(2048);
            file_put_contents($file, $generator);

            $file      = ROOT . '/../lib/salts/pass_change_token_salt.txt';
            $generator = openssl_random_pseudo_bytes(2048);
            file_put_contents($file, $generator);

            $file      = ROOT . '/../lib/salts/cookie_salt.txt';
            $generator = openssl_random_pseudo_bytes(2048);
            file_put_contents($file, $generator);

       }
       
    }
    
?>