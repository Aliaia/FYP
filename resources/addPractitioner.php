<?php

session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$userData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$userData = json_decode(json_encode($userData[0]),true);
	$userData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);
	
	if(!in_array($_POST['practitionerEmail'], $userData['Extension1']['ValueCodeableConcept'])) {

		array_push($userData['Extension1']['ValueCodeableConcept'], $_POST['practitionerEmail']);

		$practitionerData = getData('Authentication', ['Email' => $_POST['practitionerEmail']])->toArray()[0];
		$practitionerData = json_decode(json_encode($practitionerData),true);
		$practitionerID = $practitionerData['identification']['$oid'];

		$practitionerData = getData('Professionals', ['_id' => new MongoDB\BSON\ObjectID($practitionerID)])->toArray()[0];
		$practitionerData = json_decode(json_encode($practitionerData),true);
		$practitionerData['_id'] = new MongoDB\BSON\ObjectID($practitionerID);

		$PractitionerPatients = $practitionerData['Extension0']['ValueCodeableConcept'];
		array_push($PractitionerPatients, $_SESSION['login_user']['ID']);

		$practitionerData['Extension0']['ValueCodeableConcept'] = $PractitionerPatients;

		print_r($practitionerData);

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

}

?>