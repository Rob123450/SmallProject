<?php

    $inData = getRequestInfo();

    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO Users (FirstName,LastName,Email,Username,Password) VALUES (?,?,?,?,?)");
	    $stmt->bind_param("sssss", $inData["FirstName"], $inData["LastName"], $inData["Email"], $inData["Username"], $inData["Password"]);

        if( $stmt->execute())
			returnWithError("");
		else
			returnWithError("Username already exists");

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

    function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
