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

	if($placeID == null || $placeID == ''||!validatePosIntValue($placeID)){
		echo json_encode(array('message' => 'incomplete data'));
		return;
	}

	$place = getPlace($placeID);
	
	if($place['ownerID'] != $_SESSION['userID']){
		echo json_encode(array('message' => 'not owner', 'userID' => $_SESSION['userID']));
		return;
	}

	$availability = getAvailabilites($placeID);

	echo json_encode(array('message' => $availability));
?>