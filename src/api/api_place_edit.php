<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/img_upload.php');

const true_message = 'true';


function check_File_Integrity($imageName, $array_fileNames)
{
    return in_array($imageName, $array_fileNames);
}

function find_photo_in_database_array($photo_hash, $images_place_from_database, $images_place_from_database_len)
{
    for ($i = 0; $i < $images_place_from_database_len; $i++) {
        if (strcmp($photo_hash, $images_place_from_database[$i]['image']) == 0) {
            return true;
        }
    }
    return false;
}

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
    //IMAGES UPLOADED
    //
    $capacity = $_POST['capacity'];
    $ownerID = $_SESSION['userID'];

    $array_fileNames = array();

    $fileName0 = $_POST['File0'];
    $fileName1 = $_POST['File1'];
    $fileName2 = $_POST['File2'];
    $fileName3 = $_POST['File3'];
    $fileName4 = $_POST['File4'];
    $fileName5 = $_POST['File5'];


    if (isset($fileName0) && $fileName0 != "") {
        array_push($array_fileNames, $fileName0);
    }
    if (isset($fileName1) && $fileName1 != "") {
        array_push($array_fileNames, $fileName1);
    }
    if (isset($fileName2) && $fileName2 != "") {
        array_push($array_fileNames, $fileName2);
    }
    if (isset($fileName3) && $fileName3 != "") {
        array_push($array_fileNames, $fileName3);
    }
    if (isset($fileName4) && $fileName4 != "") {
        array_push($array_fileNames, $fileName4);
    }
    if (isset($fileName5) && $fileName5 != "") {
        array_push($array_fileNames, $fileName5);
    }

    $photosToRemove_str = $_POST['imagesToRemoveArray'];
    //Declare here because of the scope
    $photosToRemove;

    //Check if there is any photo to remove
    if (strlen($photosToRemove_str) != 0) {
        $photosToRemove = explode(",", $photosToRemove_str);
        $num_photos_to_remove = count($photosToRemove);
    } else {
        $num_photos_to_remove = 0;
    }

    $place_info_from_database = getPlace($placeID);

    if ($place_info_from_database['ownerID'] != $ownerID) {
        $message = 'not house owner';
    } else {

        $images_place_from_database = $place_info_from_database['images'];
        //CHECK IF THAT IMAGE BELONGS TO THE HOUSE
        for ($i = 0; $i < $num_photos_to_remove; $i++) {
            if (find_photo_in_database_array($photosToRemove[$i], $images_place_from_database, count($images_place_from_database)) === false) {
                $message = 'image not from that place';
                break;
            }
        }
    }

    $images = $_FILES['imagePlaceFile'];
    //CREATE AN ARRAY TO STORE ALL THE VALID IMAGES UPLOADED
    $images_uploaded_valid = array();
    $num_images_uploaded_valid = 0;

    //TESTS IF THERE IS ANY ERROR SO FAR
    if (strcmp($message, true_message) === 0) {

        //CHECK IF ALL PHOTOS UPLOADED ARE VALID
        $total = count($images['tmp_name']);

        for ($i = 0; $i < $total; $i++) {

            if ($images['tmp_name'][$i] != "") {

                if (check_File_Integrity($images['name'][$i], $array_fileNames) == true) {
                    if (!checkIfImageIsValid($images['tmp_name'][$i])) {
                        $message = 'invalid image';
                        break;
                    }

                    $images_uploaded_valid[$num_images_uploaded_valid] = $images['tmp_name'][$i];
                    $num_images_uploaded_valid++;
                }
            }
        }
        if (strcmp($message, true_message) === 0) {
            //Validate Inputs
            $inputs_are_valid = true;

            //TODO: TO RETURN A PERSONALIZED MESSAGE
            if (is_numeric($title)) {
                $inputs_are_valid = false;
            }
            if (is_numeric($desc)) {
                $inputs_are_valid = false;
            }
            if (is_numeric($address)) {

                $inputs_are_valid = false;
            }
            if (is_numeric($city)) {

                $inputs_are_valid = false;
            }
            if (is_numeric($country)) {
                $inputs_are_valid = false;
            }
            if (!is_numeric($numRooms)) {
                $inputs_are_valid = false;
            }
            if (!is_numeric($numBathrooms)) {

                $inputs_are_valid = false;
            }

            if (!is_numeric($capacity))
                $inputs_are_valid = false;

            if ($inputs_are_valid) {
                if (updatePlaceInfo($placeID, $title, $desc, $address, $city, $country, $numRooms, $numBathrooms, $capacity) != true) {
                    $message = 'Error Updating home';
                } else {
                    if (strcmp($message, true_message) == 0) {
                        //NEW PHOTOS UPLOADED JUST THE VALID ONES STORED AT THAT SPECIFIC ARRAY
                        for ($i = 0; $i < $num_images_uploaded_valid; $i++) {
                            if (uploadPlaceImage($placeID, $images_uploaded_valid[$i]) != true) {
                                $message = 'Invalid IMAGE uploaded';
                                break;
                            }
                        }
                        //IN ORDER TO AVOID AN ERROR OF PHOTOSTOREMOVE BEING NULL. NOT CRITICAL
                        if ($num_photos_to_remove > 0) {
                            if (deletePlaceSelectedPhotos($placeID, $photosToRemove, $num_photos_to_remove) != true) {
                                $message = 'Error removing the photo';
                            }
                        }
                    }
                }
            } else {
                $message = 'Parameters not validated';
            }
        }
    }
}
echo json_encode(array('message' => $message));
