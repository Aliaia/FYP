<?php

session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$medicationID = $_POST['id'];
	$newDrugName = $_POST['drugName'];
	$drugDosage = $_POST['dosage'];
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];

	$userData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$userData = json_decode(json_encode($userData[0]),true);
	$userData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);

	$medicationData = $userData['Extension2']['ValueCodeableConcept'];

	foreach ($medicationData as $drugName => $drugDetails) {

		if ($drugDetails[0]['$oid'] == $medicationID) {
			unset($medicationData[$drugName]);

			$medicationData[$newDrugName] = [];
			$medicationData[$newDrugName][0] = new MongoDB\BSON\ObjectID($medicationID);
			$medicationData[$newDrugName][1] = $drugDosage;
			$medicationData[$newDrugName][2] = $startDate;
			$medicationData[$newDrugName][3] = $endDate;
		}

	};

	foreach ($medicationData as $medication => $elements) {
		if (gettype($elements[0]) == 'array') {
			$medicationData[$medication][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
		 }
	};

	foreach ($userData['Extension0']['ValueCodeableConcept'] as $GraphName => $elements) {
		if (gettype($elements[0]) == 'array') {
			$userData['Extension0']['ValueCodeableConcept'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
		 }
	};

	$userData['Extension2']['ValueCodeableConcept'] = $medicationData;

	print_r($userData);

	$updateElement = new MongoDB\Driver\BulkWrite;
	$updateElement->update(

		['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
		$userData
		 
	);

	$connect->executeBulkWrite('FYP.Users', $updateElement);
	header("Location: ../Views/App/profile.php");

};



?>