<?php
include_once('../includes/session_include.php');
include_once('../includes/reservation_utils.php');
include_once('../includes/input_validation.php');

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $message='token error';
    echo json_encode(array('message' => $message));
    return;
}

if(!isset($_POST['placeID']) || !isset($_POST['checkin']) || !isset($_POST['checkout'])){
	echo json_encode(array('message' => "Invalid submission"));
	return;
}


$placeID = $_POST['placeID'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

if(!validatePosIntValue($placeID)){
	$message = 'placeID not valid';
    echo json_encode(array('message' => $message));
    return;
}
if(!validateDateValue($checkin)){
	$message = 'checkin Date not valid';
    echo json_encode(array('message' => $message));
    return;
}
if(!validateDateValue($checkout)){
	$message = 'checkout Date not valid';
    echo json_encode(array('message' => $message));
    return;
}

$price = getPriceInDate($placeID, $checkin, $checkout);

echo json_encode(array('message' => $price));

?>