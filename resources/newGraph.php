<?php

session_start();

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$graphElements = explode(',', $_POST['activeElements']);

	// $newData = [$_POST['graphName'] => $graphElements];

	$oldData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$GraphData = json_decode(json_encode($oldData[0]),true);
	$GraphData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);
	
	$GraphData['SpecifiedGraphs'][$_POST['graphName']] = $graphElements;
	
	print_r($GraphData['SpecifiedGraphs']);

	$updateElement = new MongoDB\Driver\BulkWrite;
	$updateElement->update(

		['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
		$GraphData
		 
	);

	$connect->executeBulkWrite('FYP.Users', $updateElement);
	header("Location: ../Views/App/graphs.php");

}


 ?>