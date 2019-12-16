<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/input_validation.php');

    $reservationID = $_POST['reservationID'];

    if(!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
		$message = "user not logged in";
		echo json_encode(array('message' => $message));
		return;
	}

    $message = (validatePosIntValue($reservationID) && checkIfUserCanCancelReservation($_SESSION['userID'], $reservationID) && cancelUserReservation($reservationID)) ? "yes" : "no";

    echo json_encode(array('message' => $message));
?>