<?php

		$length = 18;
		if(isset($_GET['length'])){
			$length = $_GET['length'];
		}
		$iterator = 0;
		$random = 1;
		$password = "";

		$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "y", "x", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "Y", "X", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "&apos;", "&#33;", "&#64;", "&#35;", "&#36;", "&#37;", "&#47;", "&and;", "&#42;", "&#40;", "&#41;", "&#45;", "&#95;", "&#43;", "&#126;", "&#61;", "&lt", "&gt", "&#44;", "&#46;", "&#63;", "&#58;", "&#59;", "&#39;", "&#91;", "&#93;", "&#123;", "&#124;"
		);

		while($iterator < $length){ 
			$random = rand(0, (count($chars) -1)); 
			$result = $chars[$random];
			$password .= $result;
			$iterator++; 
		}
		
		echo $password;

?>