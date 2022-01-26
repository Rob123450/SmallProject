<?php

    $inData = getRequestInfo();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    $userId = $inData["UserID"];
    $fName = $inData["FirstName"] . '%';
    $lName = $inData["LastName"] . '%';

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("SELECT ID,FirstName,LastName,AddressOne,City,State,Country,ZipCode,Email,PhoneNumber FROM Contact_Info WHERE UserID=? AND FirstName LIKE ? AND LastName LIKE ?");
        $stmt->bind_param("iss", $userId, $fName, $lName);
        $stmt->execute();

        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;

        $stmt->bind_result($ID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);

        while ($stmt->fetch()) {
            returnWithInfo($ID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);
        }

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
        printf("\n");
        echo $obj;
    }

	function returnWithInfo($ID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber)
	{
        $retValue = '{"ID":"' . $ID . '","first name":"' . $firstName . '","last name":"' . $lastName . '","address 1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zip code":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
