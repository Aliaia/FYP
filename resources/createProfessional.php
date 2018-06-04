<?php

session_start();

include 'config.php';

//Takes all the data within the create a user form, puts in the correct FHIR format, and writes it as a new document.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$_id = new MongoDB\BSON\ObjectID;
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName =  $_POST['lastName'];
	$email = $_POST['email'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$nameText = $firstName . ' ' . $middleName . ' ' . $lastName;
	$givenName = $firstName . ' ' . $middleName;

	//Check if user already exists
	$userData = getData('Authentication', ['Email' => $email])->toArray();
	$data = json_decode(json_encode($userData), true);
	
	//if user already exists, redirect back to createProfessional page
	if (count($userData) == 1) {
		$_SESSION['email_exists'] = true;
		header("Location: ../Views/CreateProfessional.php");
	
	} else {
		//Write data to Users Database
		$userdoc = [
			'_id' => $_id, 
			'Name' => ['Text' => $nameText, 'Family' => $lastName, 'Given' => $givenName],
			'Extension0' => [
				'URL' => 'AccessedPatients',
				'ValueCodeableConcept' => []
			]
		];

		writedata('Professionals', $userdoc);

		//write to authentication database
		$authenticationDoc = [
			'_id' => new MongoDB\BSON\ObjectID,
			'identification' => $_id,
			'Email' => $email,
			'password' => $password
		];

		writedata('Authentication', $authenticationDoc);
		header("Location: ../Views/professionalLogin.php");
	};

};

?>