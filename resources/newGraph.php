<?php
session_start();

include 'config.php';

//either updates the graph or creates a new one
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//Creates new graph elements
	$graphElements = [];

	$graphElements[0] = new MongoDB\BSON\ObjectID();

	//if a graphId is sent, use it, else use a new Id
	if ($_POST['graphID']) {
		$graphElements[0] = new MongoDB\BSON\ObjectID($_POST['graphID']);
	};

	//elements are sent as a string, turn into an array
	$newElements = explode(',', $_POST['activeElements']);

	//add elements to the end of the array
	$graphElements = array_merge($graphElements, $newElements);


	//Gets the old data of the graph, updates with a new graph
	$oldData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$GraphData = json_decode(json_encode($oldData[0]),true);
	$GraphData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);

	//If array exists and is being updated, update element
	if ($_POST['graphID']) {
		foreach ($GraphData['Extension0']['ValueCodeableConcept'] as $key => $value) {
			if($value[0]['$oid'] == $_POST['graphID']){
				unset($GraphData['Extension0']['ValueCodeableConcept'][$key]);
				$GraphData['Extension0']['ValueCodeableConcept'][$_POST['graphName']] = $graphElements;
				
			}
		}
	} else {

		$GraphData['Extension0']['ValueCodeableConcept'][$_POST['graphName']] = $graphElements;

	}

	//problem with JSON_decode changing variable names - format variable names
	foreach ($GraphData['Extension0']['ValueCodeableConcept'] as $GraphName => $elements) {
		if (gettype($elements[0]) == 'array') {
			$GraphData['Extension0']['ValueCodeableConcept'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
		 }
	}

	foreach ($GraphData['Extension2']['ValueCodeableConcept'] as $GraphName => $elements) {
		if (gettype($elements[0]) == 'array') {
			$GraphData['Extension2']['ValueCodeableConcept'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
		 }
	}


	// print_r($GraphData);

	//write to database, if it's a new graph it'll create a new object, else it'll update the one with the same objectID
	$updateElement = new MongoDB\Driver\BulkWrite;
	$updateElement->update(

		['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
		$GraphData
		 
	);

	$connect->executeBulkWrite('FYP.Users', $updateElement);

	// redirect to graphs page, to view new graph
	header("Location: ../Views/App/graphs.php");

}


 ?>