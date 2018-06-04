<?php

session_start();

include 'config.php';

//takes data from the login form and checks if there is a user who matches the same credentials in the authentication database
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$email = $_POST["email"];
	$enteredPassword = $_POST["password"];

	//get all data from the authentication collection where email matches
	$userData = getData('Authentication', ['Email' => $email])->toArray();
	$data = json_decode(json_encode($userData), true);

	//if there's only on value returned
	if (count($userData) == 1) {

		$data = $data[0];
		
		//verify that the hashed salted password matches the current password
		if(password_verify($enteredPassword, $data['password'])) {

			//get all personal data for the user
			$userData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($data['identification']['$oid'])]) ->toArray();

			$professionalData = getData('Professionals', ['_id' => new MongoDB\BSON\ObjectID($data['identification']['$oid'])]) ->toArray();
			if (count($userData) > 0) {
				
				$userData = json_decode(json_encode($userData), true);

				//sets the session variable with userID to be able to access the web application
				$_SESSION['login_user']= ['ID' => $data['identification']['$oid'], 'Name' => $userData[0]['Name']['Text']];
				header("Location: ../Views/App/graphs.php");
				exit;

			} elseif (count($professionalData) > 0) {

				$professionalData = json_decode(json_encode($professionalData), true);

				//sets the session variable with userID to be able to access the web application
				$_SESSION['login_user']= ['ID' => $data['identification']['$oid'], 'Name' => $professionalData[0]['Name']['Text']];
				header("Location: ../Views/App/patients.php");
				exit;
				
			}		
		}

	};

	//if user has not been found, they are redirected back to the login page
	$_SESSION['login_user'] = 'Denied';
	header("Location: ../Views/UserLogin.php");

}


?>