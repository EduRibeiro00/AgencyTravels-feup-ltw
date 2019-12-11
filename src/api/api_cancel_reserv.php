<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');

    $reservationID = $_POST['reservationID'];

    $message = cancelUserReservation($reservationID) ? "yes" : "no";

    echo json_encode(array('message' => $message));
?>