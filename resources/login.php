<?php

session_start();

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$email = $_POST["email"];
	$password = $_POST["password"];

	$userData = getData('Authentication', ['Email' => $email, 'password' => $password])->toArray();
	$data = json_decode(json_encode($userData), true);

	$personalData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($data[0]['identification']['$oid'])]) ->toArray();

	$personalData = json_decode(json_encode($personalData), true);

	if (count($userData) == 1) {

		$_SESSION['login_user']= ['ID' => $data[0]['identification']['$oid'], 'Name' => $personalData[0]['Name']['Text']];
		header("Location: ../Views/App/graphs.php");
	} else {
		$_SESSION['login_user'] = 'Denied';
		header("Location: ../Views/UserLogin.php");
	}

}


?>