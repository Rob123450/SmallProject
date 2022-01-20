<?php

require_once("./include/util.php");
session_start();

$inData = getRequestInfo();

$conn = get_database_connection();
$query = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE Username=? AND Password=?");
$query->bind_param("ss", $inData["Username"], $inData["Password"]);
$query->execute();
$result = $query->get_result();

if($row = $result->fetch_assoc())
{
	$_SESSION["UserData"] = $row;
	returnOK();
}
else
{
	returnError("Invalid username or password.");
}

?>
