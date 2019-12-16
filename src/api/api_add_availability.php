<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');
	include_once('../includes/input_validation.php');

	if(!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
		$message = 'user not logged in';
		echo json_encode(array('message' => $message));
		return;
	}

	$placeID = $_POST['placeID'];
	$avBegin = $_POST['avBegin'];
	$avEnd = $_POST['avEnd'];
	$price = $_POST['price'];

	if($placeID == null || $placeID == '' || $avBegin == null || $avBegin == '' || $avEnd == null || $avEnd == '' || $price == '' || $price == null) {
		$message = 'incomplete data';
		echo json_encode(array('message' => $message));
		return;
	}

	if(!validatePosIntValue($placeID)) {
		$message = "Invalid place";
		echo json_encode(array('message' => $message));
		return;
	}

	$place = getPlace($placeID);
	if($place['ownerID'] != $_SESSION['userID']){
		$message = 'not owner';
		echo json_encode(array('message' => $message, 'userID' => $_SESSION['userID']));
		return;
	}

	if(!validatePosIntValue($price)){
		$message = 'invalid price';
		echo json_encode(array('message' => $message));
		return;
	}

	if(!validateDateValue($avBegin) || !validateDateValue($avEnd) || $avBegin >= $avEnd || $avBegin < date('Y-m-d')){
		$message = 'invalid date';
		echo json_encode(array('message' => $message));
		return;
	}

	if(!empty(getOverlapAvailability($placeID, $avBegin, $avEnd))){
		$message = 'overlap availability';
		echo json_encode(array('message' => $message));
		return;
	}
	
	$returnValue = newAvailability($placeID, $avBegin, $avEnd, $price);

	if($returnValue === true) {
		$message = 'availability successfull';
		echo json_encode(array('message' => $message));
		return;
	}

	echo json_encode(array('message' => $returnValue));
?>