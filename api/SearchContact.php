<?php

    $inData = getRequestInfo();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    $userId = $inData["UserID"];
    $fullName = '%' . $inData["fullName"] . '%';

    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("SELECT ID,FirstName,LastName,AddressOne,City,State,Country,ZipCode,Email,PhoneNumber FROM Contact_Info WHERE UserID=? AND (CONCAT(FirstName, ' ', LastName) LIKE ? OR PhoneNumber LIKE ? OR Email LIKE ?) ORDER BY FirstName");
        $stmt->bind_param("isss", $userId, $fullName, $fullName, $fullName);
        // $stmt = $conn->prepare("SELECT ID,FirstName,LastName,AddressOne,City,State,Country,ZipCode,Email,PhoneNumber FROM Contact_Info WHERE (UserID=?) ORDER BY CONCAT(FirstName, ' ', LastName) LIKE ?, FirstName ASC");
        // $stmt->bind_param("is", $userId, $fullName);
        $stmt->execute();

        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;

        $stmt->bind_result($ID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);

        $retValue = '[';

        $temp = 0;

        while ($stmt->fetch()) {
            $temp++;
            if($temp != ($num_of_rows))
                $retValue = $retValue . '{"ID":"' . $ID . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","address1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zipCode":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""},';
            else
                $retValue = $retValue . '{"ID":"' . $ID . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","address1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zipCode":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""}';
        }
        $retValue = $retValue . ']';
        sendResultInfoAsJson($retValue);

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

	function returnWithInfo($retValue,$ID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber)
	{
        $retValue = $retValue . '{"ID":"' . $ID . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","address1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zipCode":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
