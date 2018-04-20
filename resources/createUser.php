<?php

session_start();

include 'config.php';

//Takes all the data within the create a user form, puts in the correct FHIR format, and writes it as a new document.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$_id = new MongoDB\BSON\ObjectID;
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName =  $_POST['lastName'];
	$gender = $_POST['gender'];
	$birthday = $_POST['birthday'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$nameText = $firstName . ' ' . $middleName . ' ' . $lastName;
	$givenName = $firstName . ' ' . $middleName;

	//Check if user already exists
	$userData = getData('Authentication', ['Email' => $email])->toArray();
	$data = json_decode(json_encode($userData), true);
	
	//if user already exists, redirect back to createUser page
	if (count($userData) == 1) {
		$_SESSION['email_exists'] = true;
		header("Location: ../Views/CreateUser.php");
	
	} else {
		//Write data to Users Database
		$userdoc = [
			'_id' => $_id, 
			'Name' => ['Text' => $nameText, 'Family' => $lastName, 'Given' => $givenName],
			'Gender' => $gender,
			'Birthdate' => $birthday,
			'SpecifiedGraphs' => []
			];

		writedata('Users', $userdoc);

		//write to authentication database
		$authenticationDoc = [
			'_id' => new MongoDB\BSON\ObjectID,
			'identification' => $_id,
			'Email' => $email,
			'password' => $password
		];

		writedata('Authentication', $authenticationDoc);
		header("Location: ../Views/UserLogin.php");
	};

};

?>