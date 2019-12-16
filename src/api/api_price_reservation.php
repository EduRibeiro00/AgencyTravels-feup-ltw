<?php
include_once('../includes/session_include.php');
include_once('../includes/reservation_utils.php');


if(!isset($_POST['placeID']) || !isset($_POST['checkin']) || !isset($_POST['checkout'])){
	echo json_encode(array('message' => "Invalid submission"));
	return;
}

$placeID = $_POST['placeID'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];


$price = getPriceInDate($placeID, $checkin, $checkout);

echo json_encode(array('message' => $price));

?>