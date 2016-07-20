<?php

	ini_set('session.cookie_httponly', true);
	
	define("INCLUDE_CHECK", true);
	
	session_start();
	
	/* ROOT constant has to be defined here, as it is used before Constants.class.php is required and it's method: defineConst() is invoked */
	define("ROOT", $_SERVER['DOCUMENT_ROOT']);
	
	require ROOT . '/../lib/classes/Constants.class.php';
	require ROOT . '/../lib/classes/Security.class.php';
	
	Constants::defineConst();
	Security::preventSessionHijacking();
    Security::checkTime();

?>

<?php require('views/header.php'); ?>
	<?php require('views/error_404.php'); ?>
<?php require('views/footer.php') ?>