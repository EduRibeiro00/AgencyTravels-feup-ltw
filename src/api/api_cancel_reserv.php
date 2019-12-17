<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/input_validation.php');
    include_once('../includes/reservation_utils.php');

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
		$message = 'token error';
		echo json_encode(array('message' => $message));
		return; 
	}

    $reservationID = $_POST['reservationID'];

    if(!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
		$message = "user not logged in";
		echo json_encode(array('message' => $message));
		return;
	}

    if(validatePosIntValue($reservationID) && checkIfUserCanCancelReservation($_SESSION['userID'], $reservationID)) {
        $reserv = getReservationInfo($reservationID);
        $message = (canCancelReservation($reserv['startDate']) && cancelUserReservation($reservationID)) ? "yes" : "no";
    }
    else {
        $message = "no";
    }

    echo json_encode(array('message' => $message));
?>