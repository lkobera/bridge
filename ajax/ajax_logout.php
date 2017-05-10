<?
	setcookie("userID", "", time()-(60*60*24), "/");
	setcookie("branch", "", time()-(60*60*24), "/");
	unset($_SESSION);
	session_destroy();
?>