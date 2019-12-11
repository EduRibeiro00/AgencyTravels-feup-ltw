<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/img_upload.php');

const true_message = 'true';

if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
    $message = 'user not logged in';
} else {

    $message = true_message;

    $placeID = $_POST['placeID'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $numRooms = $_POST['numRooms'];
    $numBathrooms = $_POST['numBathrooms'];
    $capacity = $_POST['capacity'];

    $images = $_FILES['imagePlaceFile']['tmp_name'];
    $photosToRemove = $_POST['imagesToRemoveArray'];

    //Check if there is any photo to remove
    if ($photosToRemove != NULL) {
        $num_photos_to_remove = count($photosToRemove);
    } else {
        $num_photos_to_remove = 0;
    }

    $ownerID = $_SESSION['userID'];
    $place_info_from_database = getPlace($placeID);

    if ($place_info_from_database['ownerID'] != $ownerID) {
        $message = 'not house owner';
    } else {

        $images_place_from_database = $place_info_from_database['images'];
        //CHECK IF THAT IMAGE BELONGS TO THE HOUSE
        for ($i = 0; $i < $num_photos_to_remove; $i++) {
            if (in_array($photo, $images_place_from_database) === false) {
                $message = 'image not from that place';
                break;
            }
        }
    }

    //TESTS IF THERE IS ANY ERROR SO FAR
    if (strcmp($message, true_message) === 0) {

        //CHECK IF ALL PHOTOS UPLOADED ARE VALID
        $total = count($images);
        for ($i = 0; $i < $total; $i++) {
            if (!checkIfImageIsValid($images[$i])) {
                $message = 'invalid image';
                break;
            }
        }

        if (strcmp($message, true_message) === 0) {
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

                $message = updatePlaceInfo($placeID, $title, $desc, $address, $city, $country, $numRooms, $numBathrooms, $capacity);


                //NEW PHOTOS UPLOADED
                for ($i = 0; $i < $total; $i++) {
                    if (uploadPlaceImage($placeID, $images[$i]) != true) {
                        $message = 'Invalid IMAGE';
                        break;
                    }
                }
            } else {
                $message = 'Parameters not validated';
            }
        }
    }
}

echo json_encode(array('message' => $message));
