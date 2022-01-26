<?php

    $inData = getRequestInfo();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $sql = "UPDATE Contact_Info SET FirstName=?,LastName=?,AddressOne=?,City=?,State=?,Country=?,ZipCode=?,Email=?,PhoneNumber=? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $inData["FirstName"], $inData["LastName"], $inData["AddressOne"], $inData["City"], $inData["State"], $inData["Country"], $inData["ZipCode"], $inData["Email"], $inData["PhoneNumber"], $inData["ID"]);
        $stmt->execute();

        $sql = "SELECT FirstName,LastName,AddressOne,City,State,Country,ZipCode,Email,PhoneNumber FROM Contact_Info WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",  $inData["ID"]);
        $stmt->execute();

        $stmt->store_result();

        $num_of_rows = $stmt->num_rows;

        $stmt->bind_result($firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);

        while ($stmt->fetch()) {
            returnWithInfo($firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);
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
        echo $obj;
    }

	function returnWithInfo($firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber)
	{
        $retValue = '{"first name":' . $firstName . ',"last name":"' . $lastName . '","address 1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zip code":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
