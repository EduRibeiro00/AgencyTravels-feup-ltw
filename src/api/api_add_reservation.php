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
	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];
	
	if($placeID == null || $placeID == '' || $checkin == null || $checkin == '' || $checkout == null || $checkout == ''){
		$message = 'incomplete data';
		echo json_encode(array('message' => $message));
		return;
	}

	if(!(validatePosIntValue($placeID) && validateDateValue($checkin) && validateDateValue($checkout) && $checkin < $checkout && $checkin >= date('Y-m-d'))) {
		$message = 'invalid inputs';
		echo json_encode(array('message' => $message));
		return;
	}

	$price = getPriceInDate($placeID, $checkin, $checkout);
	if($price < 0){
		$message = 'invalid dates';
		echo json_encode(array('message' => $message));
		return;
	}

	$finalPrice = $price * timeToDay(strtotime($checkout) - strtotime($checkin));
	$returnValue = newReservation($_SESSION['userID'], $checkin, $checkout, $finalPrice, $placeID);

	if($returnValue === true) {
		$message = 'reservation successful';
		echo json_encode(array('message' => $message));
		return;
	}

	echo json_encode(array('message' => $returnValue));
?>