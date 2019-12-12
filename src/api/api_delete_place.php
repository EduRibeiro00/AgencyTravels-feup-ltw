<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/img_upload.php');

    $placeID = $_POST['placeID'];

    deletePlaceAllImages($placeID);
    $message = deletePlace($placeID) ? "yes" : "no";

    echo json_encode(array('message' => $message));
?>