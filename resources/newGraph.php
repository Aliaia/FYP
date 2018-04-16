<?php

session_start();

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//New graph Elements
	$graphElements = [];
	$graphElements[0] = new MongoDB\BSON\ObjectID();
	if ($_POST['graphID']) {
		$graphElements[0] = new MongoDB\BSON\ObjectID($_POST['graphID']);
	};
	$newElements = explode(',', $_POST['activeElements']);
	$graphElements = array_merge($graphElements, $newElements);

	//Gets the old data of the graph, updates with a new graph
	$oldData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
	$GraphData = json_decode(json_encode($oldData[0]),true);
	$GraphData['_id'] = new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID']);

	//If array exists and is being updated, update element
	if ($_POST['graphID']) {
		foreach ($GraphData['SpecifiedGraphs'] as $key => $value) {
			if($value[0]['$oid'] == $_POST['graphID']){
				unset($GraphData['SpecifiedGraphs'][$key]);
				$GraphData['SpecifiedGraphs'][$_POST['graphName']] = $graphElements;
				
			}
		}
	} else {

		$GraphData['SpecifiedGraphs'][$_POST['graphName']] = $graphElements;

	}

	foreach ($GraphData['SpecifiedGraphs'] as $GraphName => $elements) {
		if (gettype($elements[0]) == 'array') {
			$GraphData['SpecifiedGraphs'][$GraphName][0] = new MongoDB\BSON\ObjectID($elements[0]['$oid']);
		 }
	}

	$updateElement = new MongoDB\Driver\BulkWrite;
	$updateElement->update(

		['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])],
		$GraphData
		 
	);

	$connect->executeBulkWrite('FYP.Users', $updateElement);
	header("Location: ../Views/App/graphs.php");

}


 ?>