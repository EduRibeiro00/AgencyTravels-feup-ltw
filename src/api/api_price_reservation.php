<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');


$placeID = $_POST['placeID'];
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];


$message=getCompatibleAvailability($placeID,$check_in_date,$check_out_date);

echo json_encode(array('message' => $message));

?>