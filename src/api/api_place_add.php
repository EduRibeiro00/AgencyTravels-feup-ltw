<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_location.php');
include_once('../includes/img_upload.php');

const true_message = 'true';

if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
    $message = 'user not logged in';
} else {
    $message = true_message;

    $ownerID = $_POST['userID'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $numRooms = $_POST['numRooms'];
    $numBathrooms = $_POST['numBathrooms'];
    $capacity = $_POST['capacity'];

    $images = $_FILES['imagePlaceFile']['tmp_name'];
    //CREATE AN ARRAY TO STORE ALL THE VALID IMAGES UPLOADED
    $images_uploaded_valid = array();
    $num_images_uploaded_valid = 0;

    //CHECK IF ALL PHOTOS UPLOADED ARE VALID
    $total = count($images);

    for ($i = 0; $i < $total; $i++) {

        if ($images[$i] != "") {

            if (!checkIfImageIsValid($images[$i])) {
                $message = 'invalid image';
                break;
            }

            $images_uploaded_valid[$num_images_uploaded_valid] = $images[$i];
            $num_images_uploaded_valid++;
        }
    }

    //IF THE ERROR MESSAGE WAS NOT TRIGGERED, CONTINUE
    if (strcmp($message, 'invalid image') > 0) {

        //Validate Inputs
        if (
            !is_numeric($title) &&
            !is_numeric($desc) &&
            !is_numeric($address) &&
            !is_numeric($city) &&
            !is_numeric($country) &&
            is_numeric($numRooms) &&
            is_numeric($numBathrooms) &&
            is_numeric($capacity)
        ) {


            //WHEN WE INSERT A NEW PLACE WE MUST FIRST CHECK IF THERE IS ALREADY A LOCATION WITH THAT ID, IF NOT -> CREATE
            $array_locations = locationGetID($city, $country);

            //IF LOCATION IS EMPTY, WE MUST CREATE THIS NEW LOCATION

            if ($array_locations == false) {

                if (locationInsert($city, $country) != true) {
                    $message = 'Error while inserting location new';
                }
                $locationID = locationGetID($city, $country)['locationID'];
            } else {
                $locationID = $array_locations['locationID'];
            }

            //IF INSERTED NEW LOCATION OR NOT THE ID OF THAT LOCATION CANNOT BE NULL
            if (!is_numeric($locationID)) {
                $message = 'Location ID NULL';
            } else {

                if (newPlace($title, $desc, $address, $locationID, $numRooms, $numBathrooms, $capacity, $ownerID) == true) {
                    //GET THE NEW PLACE ID
                    $placeID = getPlaceID($title, $address, $ownerID)['placeID'];

                    for ($i = 0; $i < $num_images_uploaded_valid; $i++) {
                        if (uploadPlaceImage($placeID, $images_uploaded_valid[$i]) != true) {
                            $message = 'Invalid IMAGE';
                            break;
                        }
                    }
                } else {
                    $message = 'Error while inserting a new place';
                }
            }
        }
    } else {
        $message = 'Parameters not validated';
    }
}

echo json_encode(array('message' => $message));
