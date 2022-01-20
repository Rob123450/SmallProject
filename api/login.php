<?php

// Import shared functions and open PHP session
require_once("./include/util.php");
session_start();

// Decode input JSON
$inData = getRequestInfo();

// Create connection to COP4331 database
$conn = get_database_connection();

// Prepare and execute query to search for matching user
$query = $conn->prepare("SELECT ID, FirstName, LastName, Email FROM Users WHERE Username=? AND Password=?");
$query->bind_param("ss", $inData["Username"], $inData["Password"]);
$query->execute();
$result = $query->get_result();

// If such a user is found, return OK and update PHP session with the info of the logged in user
if($row = $result->fetch_assoc())
{
	$update = $conn->prepare("UPDATE Users SET DateLastLoggedIn=NOW() WHERE ID=?");
	$update->bind_param("i", $row["ID"]);
	$update->execute();
	
	$update->close();
	$query->close();
	$conn->close();
	
	$_SESSION["UserData"] = $row;
	returnOK();
}
// Otherwise, indicate error
else
{
	$query->close();
	$conn->close();
	
	returnError("Invalid username or password.");
}

?>
