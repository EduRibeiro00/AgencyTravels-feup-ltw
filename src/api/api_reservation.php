<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');
	include_once('../includes/input_validation.php');

	if ($_SESSION['csrf'] !== $_POST['csrf']) {
		$message='token error';
		echo json_encode(array('message' => $message));
		return;
	}
	

	if(!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
		$message = 'user not logged in';
		return;
	}

	$placeID = $_POST['placeID'];
	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];


	if(!validatePosIntValue($placeID)){
		$message='invalidPlaceID';
		echo json_encode(array('message' => $message));        
        return;
	}
	if(!validateDateValue($checkin)){
		$message='invalid CheckInDates';
		echo json_encode(array('message' => $message));        
        return;
	}
	if(!validateDateValue($checkout)){
		$message='invalid CheckOutDate';
		echo json_encode(array('message' => $message));        
        return;
	}
	
	if($placeID == null || $placeID == '' || $checkin == null || $checkin == '' || $checkout == null || $checkout == ''){
		echo json_encode(array('message' => 'incomplete data'));
		return;
	}

	$price = getPriceInDate($placeID, $checkin, $checkout);
	switch ($price) {
		case -1:
			echo json_encode(array('message' => 'reservation overlap'));
			return;
		case -2:
			echo json_encode(array('message' => 'inexsitent availability'));
			return;
		case -3:
			echo json_encode(array('message' => 'incomplete data'));
			return;
	}

	if(userHasReservationsInRange($_SESSION['userID'], $checkin, $checkout)){
		echo json_encode(array('message' => 'overlap own reservation', 'price' => $price));
		return;
	}

	if($_SESSION['userID'] == getPlace($placeID)['ownerID']){
		echo json_encode(array('message' => 'own place', 'price' => $price));
		return;
	}

	echo json_encode(array('message' => 'valid reservation', 'price' => $price));
?>