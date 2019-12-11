<?php
include_once('../templates/tpl_reservation_utils.php');


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