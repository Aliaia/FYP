<?php

session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$userData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$userData = json_decode(json_encode($userData[0]),true);
	$userData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);

	$medicationData = $userData['Extension2']['ValueCodeableConcept'];

	$medicationData[$_POST['name']][0] = new MongoDB\BSON\ObjectID();
	$medicationData[$_POST['name']][1] = $_POST['dosage'];
	$medicationData[$_POST['name']][2] = $_POST['startDate'];
	$medicationData[$_POST['name']][3] = $_POST['endDate'];

	$userData['Extension2']['ValueCodeableConcept'] = $medicationData;

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

	print_r($userData);

	$updateElement = new MongoDB\Driver\BulkWrite;
	$updateElement->update(

		['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
		$userData
		 
	);

	$connect->executeBulkWrite('FYP.Users', $updateElement);
	header("Location: ../Views/App/profile.php");


}
?>