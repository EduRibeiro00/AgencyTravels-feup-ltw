<?php

include_once('../database/db_places.php');
include_once('../includes/input_validation.php');

$city = $_POST['city'];
$country = $_POST['country'];

if(!validateLocationValue($city) || !validateLocationValue($country)) {
    $message = "no";
    echo json_encode(array('message' => $message));
    return;
}

$coords_array = getAllCoordinatesLocation($country,$city);
echo json_encode(array('message' => $coords_array));
?>