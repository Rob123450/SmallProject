
<?php

    $inData = getRequestInfo();
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Username=? AND Password=?");
		$stmt->bind_param("ss", $inData["Username"], $inData["Password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if($row = $result->fetch_assoc())
			returnWithInfo( $row['FirstName'], $row['LastName'], $row['ID'] );
		else
			returnWithError("No Records Found");

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

    function returnWithError($err)
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
	}

	function returnWithInfo($firstName, $lastName, $id)
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson($retValue);
	}
?>
