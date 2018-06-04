<?php
	
	//clears the php session
	session_start();

	unset($_SESSION['login_user']);
	header("Location: ../Views/AboutUs.html");

?>