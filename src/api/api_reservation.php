<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');

	if ((!isset($_SESSION['userID']) && !validateIntValue($_SESSION['userID'])) || $_SESSION['userID'] == '') {
		$message = 'user not logged in';
		return;
	}

	$placeID = $_POST['placeID'];
	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];


	if(!validateIntValue($placeID)){
		$message='invalidPlaceID';
		echo json_encode(array('message' => $message));        
        return;
	}
	if(!validateDate($checkin)){
		$message='invalid CheckInDates';
		echo json_encode(array('message' => $message));        
        return;
	}
	if(!validateDate($checkout)){
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