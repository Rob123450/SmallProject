<?php

// Create and return a connection to the `COP4331` mysql database using the default user for the project
function get_database_connection()
{
	$conn = new mysqli("localhost", "default", $_ENV["DEFAULT_MYSQL_PASSWD"], "COP4331");
	
	if($conn->connect_error) returnError($conn->connect_error);
		
	else return $conn;
}

// Decode the JSON input from the front-end into a PHP array
function getRequestInfo()
{
	return json_decode(file_get_contents('php://input'), true);
}

// Encode a PHP array into JSON and return to front-end
function sendResultInfoAsJson($obj)
{
	header('Content-type: application/json');
	echo json_encode($obj);
}

// Shortcut to return OK, indicating normal operation
function returnOK($obj = array())
{
	$obj["ok"] = true;
	sendResultInfoAsJson($obj);
}

function returnError($err, $obj = array())
{
	$obj["ok"] = false;
	$obj["error"] = $err;
	sendResultInfoAsJson($obj);
}

?>
