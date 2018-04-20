<?php

session_start();

include 'config.php';

//takes data from the login form and checks if there is a user who matches the same credentials in the authentication database
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$email = $_POST["email"];
	$password = $_POST["password"];

	//gt all data from the authentication collection where email and password are the same
	$userData = getData('Authentication', ['Email' => $email, 'password' => $password])->toArray();
	$data = json_decode(json_encode($userData), true);

	print_r($data[0]['identification']);

	$personalData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($data[0]['identification']['$oid'])]) ->toArray();

	$personalData = json_decode(json_encode($personalData), true);

	//if there is one user, log in
	//uses PHP sessions to save user ID to get their data within the application
	if (count($userData) == 1) {
		$_SESSION['login_user']= ['ID' => $data[0]['identification']['$oid'], 'Name' => $personalData[0]['Name']['Text']];
		header("Location: ../Views/App/graphs.php");
	} else {
		$_SESSION['login_user'] = 'Denied';
		header("Location: ../Views/UserLogin.php");
	}

}


?>