<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');
	include_once('../includes/input_validation.php');

	if ((!isset($_SESSION['userID']) || !validateIntValue($_SESSION['userID'])) || $_SESSION['userID'] == '') {
		$message = 'user not logged in';
		echo json_encode(array('message' => $message));
		return;
	}
	$placeID = $_POST['placeID'];

	if($placeID == null || $placeID == ''||!validateIntValue($placeID)){
		echo json_encode(array('message' => 'incomplete data'));
		return;
	}

	$place = getPlace($placeID);
	
	if($place['ownerID'] != $_SESSION['userID']){
		echo json_encode(array('message' => 'not owner', 'userID' => $_SESSION['userID']));
		return;
	}
	// END: Até aqui

	$availability = getAvailabilites($placeID);

	//echo json_encode(array('message' => $availability));
	echo json_encode(array('message' => $availability));
?>