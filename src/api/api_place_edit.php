<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
    // verify if user is already logged in
    /*
    
    //TODO:Apos as sessions estarem, alterar
    if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
        $message = 'user not logged in';
    }
    else {
        */
        
        $placeID = $_POST['placeID'];
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $numRooms = $_POST['numRooms'];
        $numBathrooms = $_POST['numBathrooms'];
        $capacity = $_POST['capacity'];

        $message=updatePlaceInfo($placeID,$title,$desc,$address,$city,$country,$numRooms,$numBathrooms,$capacity);
      

        
        
    //}
    echo json_encode(array('message' => $message));
?>