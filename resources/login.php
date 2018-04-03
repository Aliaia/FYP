<?php

session_start();

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST["email"];
	$password = $_POST["password"];

	$userData = getData('Authentication', ['Email' => $email, 'Password' => $password])->toArray();

	$data = json_decode(json_encode($userData), true);

	if (count($userData) == 1) {
		$_SESSION['login_user']= $data[0]['Identification'];
		header("Location: ../Views/App/graphs.html");
	} else {
		$_SESSION['login_user'] = 'Denied';
		header("Location: ../Views/UserLogin.php");
	}

}


?>