<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/img_upload.php');
    include_once('../includes/input_validation.php');
    include_once('../database/db_places.php');

    $placeID = $_POST['placeID'];

    if(validatePosIntValue($placeID)) {

        $place = getPlace($placeID);
        if($place['ownerID'] != $_SESSION['userID']){
            $message = 'not owner';
            echo json_encode(array('message' => $message, 'userID' => $_SESSION['userID']));
            return;
        }

        deletePlaceAllImages($placeID);
        $message = deletePlace($placeID) ? "yes" : "no";
    }
    else {
        $message = "no";
    }

    echo json_encode(array('message' => $message));
?>