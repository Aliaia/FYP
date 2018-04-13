<?php

session_start();

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// print_r($_POST);

	$_id = new MongoDB\BSON\ObjectID;
	$date = date('Y-m-d');
	$time =  date('h:i:s');
	$DateTime = $date . 'T' . $time . 'Z';

	$ConditionData = [];
	$ConditionData['_id'] = $_id;
	$ConditionData['Identification'] = $_SESSION['login_user']['ID'];
	$ConditionData['DateTime'] = $DateTime;

	foreach ($_POST as $key => $value) {
		if($value['Measure'] != ""){
			$ConditionData[$key] = ['Measure' => intval($value['Measure']), 'Unit' => $value['Unit']];
		};
	};

	writedata('ConditionData', $ConditionData);
	header("Location: ../Views/App/graphs.php");

}


 ?>