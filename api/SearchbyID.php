<?php

    $inData = getRequestInfo();
    $conn = new mysqli("localhost", "admin", "g53GkjwjZv", "COP4331");

    $ID = $inData["ID"];
    if($conn->connect_error)
    {
        returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("SELECT * FROM Contact_Info WHERE ID = ?");
        $stmt->bind_param("i", $ID);

        if($stmt->execute())
			echo "";
		else
			echo "Error: Could Not obtain Contact";

        $stmt->store_result();

        $stmt->bind_result($ID,$UserID,$firstName,$lastName,$addressOne,$city,$state,$country,$zipCode,$email,$phoneNumber);
        $stmt->fetch();
        
        $retValue = '{"ID":"' . $ID . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","address1":"' . $addressOne . '","city":"' . $city . '","state":"' . $state . '","country":"' . $country . '","zipCode":"' . $zipCode . '","email":"' . $email . '","phoneNumber":"' . $phoneNumber . '","error":""}';

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
?>
