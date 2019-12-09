<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_images.php');

    if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
        $message = 'user not logged in';
    }
    else {
        
        $placeID = $_POST['placeID'];
        
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $numRooms = $_POST['numRooms'];
        $numBathrooms = $_POST['numBathrooms'];
        $capacity = $_POST['capacity'];
        
        //Validate Inputs
        if(
            !is_numeric($title)&&
            !is_numeric($desc)&&
            !is_numeric($address)&&
            !is_numeric($city)&&
            !is_numeric($country)&&
            is_numeric($numRooms)&&
            is_numeric($numBathrooms)&&
            is_numeric($capacity)
        ){

            $message=updatePlaceInfo($placeID,$title,$desc,$address,$city,$country,$numRooms,$numBathrooms,$capacity);
        }
        else{
            $message='Parameters not validated';
        }

         
    }

    echo json_encode(array('message' => $message));
?>