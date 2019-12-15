<?php

include_once('../database/db_places.php');

$city=$_POST['city'];
$country=$_POST['country'];

$coords_array=getAllCoordinatesLocation($country,$city);

    echo json_encode(array('message' => $coords_array));
?>