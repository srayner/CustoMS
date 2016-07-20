<?php
	
	if(!defined('INCLUDE_CHECK')) die('You probably shouldn\'t be looking around in here..');
	
	class Database{

		public function connect(){
			
			$init['db'] = array(
				
				/* Edit the configuration settings below to connect to your own database */

				//local server
				'host' 		=> 'localhost',
				'username' 	=> 'root',
				'password' 	=> 'root',
				'dbname' 	=> 'AustroAsianTimes'
				
			);
			
			return new PDO('mysql:host='.$init['db']['host'].';dbname='.$init['db']['dbname'], $init['db']['username'], $init['db']['password']);			
		}
	}
	
?>