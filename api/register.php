<?php

// Import shared functions and open PHP session
require_once("./include/util.php");
session_start();

// Decode input JSON
$inData = getRequestInfo();

// Create connection to COP4331 database
$conn = get_database_connection();

// Prepare and execute query to insert new user
$query = $conn->prepare("INSERT into Users (FirstName, LastName, Email, Username, Password) VALUES (?,?,?,?,?)");
$query->bind_param("sssss", $inData["FirstName"], $inData["LastName"], $inData["Email"], $inData["Username"], $inData["Password"]);
$query->execute();

// Check for success
if($query->errno == 0)
{
	$query->close();
	$conn->close();
	
	returnOK();
}
else
{
	$query->close();
	$conn->close();
	
	returnError($query->error);
}

?>
