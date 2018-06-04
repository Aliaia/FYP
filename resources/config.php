<?php
// Connects to the MongoDB database with password and username
$connect = new MongoDB\Driver\Manager('mongodb+srv://Test1:Test1@test-mn8az.mongodb.net');

//gets the data held in the specified collection with the query
function getData($database, $query) {
	global $connect;

	$fullquery = new MongoDB\Driver\Query($query);
	$FullDatabase = 'FYP.' . $database; 
	
	$cursor = $connect->executeQuery($FullDatabase, $fullquery);

	return $cursor;
}


// Gets the data from the conditions database, with the specified ID
function getConditionData($ID) {
	global $connect;

	$query = new MongoDB\Driver\Query(['Identification' => $ID]);
	$cursor = $connect->executeQuery('FYP.ConditionData', $query);
	return $cursor;
}

// Creates a new document in the collection specified
function writeData($database, $data){
	global $connect;

	$write = new MongoDB\Driver\BulkWrite;
	$write->insert($data);
	$connect->executeBulkWrite('FYP.' . $database, $write);
}

function getPatients($ID) {
	global $connect;
	$names = [];

	$query = new MongoDB\Driver\Query(['_id' => new MongoDB\BSON\ObjectID($ID)]);
	$cursor = $connect->executeQuery('FYP.Professionals', $query)->toArray();

	$professionalData = json_decode(json_encode($cursor), true);
	$patients = $professionalData[0]['Extension0']['ValueCodeableConcept'];

	foreach ($patients as $patient) {
		$patient = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($patient)]) ->toArray();
		$patient = json_decode(json_encode($patient), true);
		$patient = [$patient[0]['Name']['Text'], $patient[0]['Birthdate'], $patient[0]['_id']];
		array_push($names, $patient);
	}

	return $names;
}

?>