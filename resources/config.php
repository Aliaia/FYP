<?php

$connect = new MongoDB\Driver\Manager('mongodb+srv://Test1:Test1@test-mn8az.mongodb.net');

function getData($database, $query) {
	global $connect;

	$fullquery = new MongoDB\Driver\Query($query);
	$FullDatabase = 'FYP.' . $database; 
	
	$cursor = $connect->executeQuery($FullDatabase, $fullquery);

	return $cursor;
}

function getConditionData($ID) {
	global $connect;

	$query = new MongoDB\Driver\Query(['Identification' => $ID]);
	$cursor = $connect->executeQuery('FYP.ConditionData', $query);
	return $cursor;
}

function writeData($database, $data){
	global $connect;

	$write = new MongoDB\Driver\BulkWrite;
	$write->insert($data);
	$connect->executeBulkWrite('FYP.' . $database, $write);
}

?>