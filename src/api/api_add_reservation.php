<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');

	if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
		echo json_encode(array('message' => 'user not logged in'));
		return;
	}

	$placeID = $_POST['placeID'];
	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];
	
	if($placeID == null || $placeID == '' || $checkin == null || $checkin == '' || $checkout == null || $checkout == ''){
		echo json_encode(array('message' => 'incomplete data'));
		return;
	}

	$price = getPriceInDate($placeID, $checkin, $checkout);
	if($price < 0){
		echo json_encode(array('message' => 'invalid dates'));
		return;
	}

	$finalPrice = $price * timeToDay(strtotime($checkout) - strtotime($checkin));
	$returnValue = newReservation($_SESSION['userID'], $checkin, $checkout, $finalPrice, $placeID);

	// TODO: verificar isto
	if($returnValue === true) {
		echo json_encode(array('message' => 'reservation successfull'));
		return;
	}

	echo json_encode(array('message' => $returnValue));
?>