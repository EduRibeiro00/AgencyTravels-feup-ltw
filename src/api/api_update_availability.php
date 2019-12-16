<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');

	// TODO: acho que estas verificações são desnecessárias
	if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
		echo json_encode(array('message' => 'user not logged in'));
		return;
	}

	$placeID = $_POST['placeID'];

	if($placeID == null || $placeID == ''){
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