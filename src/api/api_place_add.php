<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_location.php');
include_once('../includes/img_upload.php');
include_once('../includes/place_forms.php');
include_once('../includes/input_validation.php');

const true_message = 'true';


if (!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
    $message = 'user not logged in';
} else {
    $message = true_message;
    $Duplicates = false;
    $ownerID = $_POST['userID'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $address = $_POST['address'];
    $numRooms = $_POST['numRooms'];
    $numBathrooms = $_POST['numBathrooms'];
    $capacity = $_POST['capacity'];
    $locationID = $_POST['location'];
    $GPSCoords = $_POST['gpsCoords'];

    //Retrevie the 6 possible file to add
    $array_fileNames = buildArrayWithFilesToAdd();

    //FILES HAS WHAT WE WANT TO ADD + WHAT WE DONT WANT TO ADD
    $images = $_FILES['imagePlaceFile'];
    //CREATE AN ARRAY TO STORE ALL THE VALID IMAGES UPLOADED
    $images_uploaded_valid = array();
    $num_images_uploaded_valid = 0;

    //CHECK IF ALL PHOTOS UPLOADED ARE VALID
    $total = count($images['tmp_name']);

    for ($i = 0; $i < $total; $i++) {

        if ($images['tmp_name'][$i] != "") {

            if (check_File_Integrity($images['name'][$i], $array_fileNames, $Duplicates) == true) {

                if (!checkIfImageIsValid($images['tmp_name'][$i])) {
                    $message = 'invalid image';
                    break;
                }
                //HERE WILL BE JUST THE PHOTOS THAT ARE VALID AND WHOSE NAME EXISTS IN THE ARRAY OF PHOTOS TO UPLOAD
                $images_uploaded_valid[$num_images_uploaded_valid] = $images['tmp_name'][$i];
                $num_images_uploaded_valid++;
            }
        }
    }

    //TEST THE NUMBER O FILES UPLOADED IS NOT EMPTY AND IF NOT MORE THAN 6 (5+1- STARTS AT 0)
    if ($num_images_uploaded_valid < 1 || $num_images_uploaded_valid > 6) {
        $message = 'Number of photos invalid';
    } else {

        //IF THE ERROR MESSAGE WAS NOT TRIGGERED, CONTINUE
        if (strcmp($message, true_message) == 0) {

            //Validate Inputs
            $inputs_are_valid = true;

            if (!is_numeric($ownerID) || !validatePosIntValue($ownerID)) {
                $message = 'ownerID not valid';
                $inputs_are_valid = false;
            }

            if ($ownerID != $_SESSION['userID']) {
                $message = 'ownerID dont match';
                $inputs_are_valid = false;
            }

            if (is_numeric($title) || !validateTextValue($title)) {
                $message = 'Title not valid';
                $inputs_are_valid = false;
            }
            if (is_numeric($desc) || !validateTextValue($desc)) {
                $message = 'Description not valid';
                $inputs_are_valid = false;
            }
            if (is_numeric($address) || !validateLocationValue($address)) {
                $message = 'Address not valid';
                $inputs_are_valid = false;
            }
            if (!is_numeric($numRooms) || !validatePosIntValue($numRooms)) {
                $message = 'Number of rooms is not valid';
                $inputs_are_valid = false;
            }
            if (!is_numeric($numBathrooms) || !validatePosIntValue($numBathrooms)) {
                $message = 'Number of Bathrooms is not valid';
                $inputs_are_valid = false;
            }
            if (!is_numeric($capacity) || !validatePosIntValue($capacity)) {
                $message = 'Capacity is not valid';
                $inputs_are_valid = false;
            }
            if (!is_numeric($locationID) || !validatePosIntValue($locationID)) {
                $message = false;
                $inputs_are_valid = false;
            }
            /*PARSE THE GPS COORDS WE WILL NEED TO EXPLODE THE STRING. THEY ARE INSERTED AS A STRING TO THE DATABASE*/

            if (validateGPSCoords($GPSCoords) == false) {
                $message = 'GPS Coords of that Address invalid';
                $inputs_are_valid = false;
            }
            //IF INSERTED NEW LOCATION OR NOT THE ID OF THAT LOCATION CANNOT BE NULL

            $placeID = newPlace($title, $desc, $address, $GPSCoords, $locationID, $numRooms, $numBathrooms, $capacity, $ownerID);
            
            if ($placeID != false) {
                
                for ($i = 0; $i < $num_images_uploaded_valid; $i++) {
                    if (uploadPlaceImage($placeID, $images_uploaded_valid[$i]) != true) {
                        $message = 'Invalid IMAGE';
                        break;
                    }
                }
            }else{
                $message = 'Fail create new place';
            }
        }
    }

    if ($Duplicates == true) {
        $message = 'Duplicate Images';
    }
}

echo json_encode(array('message' => $message));
