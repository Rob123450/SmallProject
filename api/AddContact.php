<?php

    $inData = getRequestInfo();

    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO Contact_Info (UserID,FirstName,LastName,AddressOne,City,State,Country,ZipCode,Email,PhoneNumber) VALUES (?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("isssssssss", $inData["UserID"], $inData["FirstName"], $inData["LastName"], $inData["AddressOne"], $inData["City"], $inData["State"], $inData["Country"], $inData["ZipCode"], $inData["Email"], $inData["PhoneNumber"]);

        if($stmt->execute())
			echo "Successfully Created a Contact";
		else
			echo "Could not create a contact";

		$stmt->close();
		$conn->close();
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson()
    {
        header('Content-Type: application/json');
        echo $obj;
    }

?>
