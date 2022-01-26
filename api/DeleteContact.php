<?php

    $inData = getRequestInfo();
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("DELETE FROM Contact_Info WHERE UserID=? AND PhoneNumber=?");
		$stmt->bind_param("is", $inData["UserID"], $inData["PhoneNumber"]);

		if($stmt->execute())
			echo "Successfully Deleted Contact";
		else
			echo "Error: Could Not Delete Contact";

		$stmt->close();
		$conn->close();
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-Type: application/json');
        echo $obj;
    }

?>
