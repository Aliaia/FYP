<?php

session_start();
include 'config.php';

	$userData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$userData = json_decode(json_encode($userData[0]),true);
	$userData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);

	if(in_array($_GET['practitioner'], $userData['Extension1']['ValueCodeableConcept'])){

		$value = array_search($_GET['practitioner'], $userData['Extension1']['ValueCodeableConcept']);

		unset($userData['Extension1']['ValueCodeableConcept'][$value]);


		$practitionerData = getData('Authentication', ['Email' => $_GET['practitioner']])->toArray()[0];
		$practitionerData = json_decode(json_encode($practitionerData),true);
		$practitionerID = $practitionerData['identification']['$oid'];

		$practitionerData = getData('Professionals', ['_id' => new MongoDB\BSON\ObjectID($practitionerID)])->toArray()[0];

		$practitionerData = json_decode(json_encode($practitionerData),true);
		$practitionerData['_id'] = new MongoDB\BSON\ObjectID($practitionerID);

		$PractitionerPatients = $practitionerData['Extension0']['ValueCodeableConcept'];

		$value = array_search($_SESSION['login_user']['ID'], $practitionerData['Extension0']['ValueCodeableConcept']);

		if ($value) {

			unset($practitionerData['Extension0']['ValueCodeableConcept'][$value]);

			$updateElement = new MongoDB\Driver\BulkWrite;
			$updateElement->update(

				['_id' => new MongoDB\BSON\ObjectID($practitionerID)],
				$practitionerData
			 
			);

			$connect->executeBulkWrite('FYP.Professionals', $updateElement);
		};


		foreach ($userData['Extension0']['ValueCodeableConcept'] as $GraphName => $elements) {
			if (gettype($elements[0]) == 'array') {
				$userData['Extension0']['ValueCodeableConcept'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
			 }
		};

		foreach ($userData['Extension2']['ValueCodeableConcept'] as $GraphName => $elements) {
			if (gettype($elements[0]) == 'array') {
				$userData['Extension2']['ValueCodeableConcept'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
			 }
		};

		$updateElement = new MongoDB\Driver\BulkWrite;
		$updateElement->update(

			['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
			$userData
			 
		);

		$connect->executeBulkWrite('FYP.Users', $updateElement);
		header("Location: ../Views/App/profile.php");
	
	};


?>