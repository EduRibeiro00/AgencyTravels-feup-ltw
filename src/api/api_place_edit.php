<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/img_upload.php');
include_once('../includes/place_forms.php');
include_once('../includes/input_validation.php');

const true_message = 'true';

function find_photo_in_database_array($photo_hash, $images_place_from_database)
{
    $images_place_from_database_len = count($images_place_from_database);
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
    $numRooms = $_POST['numRooms'];
    $numBathrooms = $_POST['numBathrooms'];
    $locationID = $_POST['location'];
    $GPSCoords = $_POST['gpsCoords'];
    //IMAGES UPLOADED
    //
    $capacity = $_POST['capacity'];
    $ownerID = $_SESSION['userID'];


    if(!is_numeric($ownerID)&&!validateIntValue($ownerID)){
        $message = 'ownerID not valid';
        echo json_encode(array('message' => $message));
        return;
    }

    $array_fileNames = buildArrayWithFilesToAdd();

    $num_photos_to_initial = getNumberOfImagesForPlace($placeID)['nImages'];

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
        //CHECK IF THOSE IMAGES TO REMOVE BELONG TO THE HOUSE
        for ($i = 0; $i < $num_photos_to_remove; $i++) {
            if (find_photo_in_database_array($photosToRemove[$i], $images_place_from_database) === false) {
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
        //TEST IF THE EDIT FORM MANTAINS 1 PHOTO AFTER ALL THE OPERATIONS.
        if (($num_photos_to_initial + $num_images_uploaded_valid - $num_photos_to_remove) < 1) {
            $message = 'A place Must Have at least one image';
        } else if (($num_photos_to_initial + $num_images_uploaded_valid - $num_photos_to_remove) > 6) {
            //TEST IF THE NUMBER OF PHOTOS IS >6
            $message = 'A place Must have a maximum six images';
        } else {
            if (strcmp($message, true_message) === 0) {
                //Validate Inputs
                $inputs_are_valid = true;

                if(!is_numeric($ownerID)||!validateIntValue($ownerID)){
                    $message = 'ownerID not valid';
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
                if (!is_numeric($numRooms) || !validateIntValue($numRooms)) {
                    $message = 'Number of rooms is not valid';
                    $inputs_are_valid = false;
                }
                if (!is_numeric($numBathrooms) || !validateIntValue($numBathrooms)) {
                    $message = 'Number of Bathrooms is not valid';
                    $inputs_are_valid = false;
                }
                if (!is_numeric($capacity) || !validateIntValue($capacity)) {
                    $message = 'Capacity is not valid';
                    $inputs_are_valid = false;
                }
                if (!is_numeric($locationID) || !validateIntValue($locationID)) {
                    $message = false;
                    $inputs_are_valid = false;
                }
                /*PARSE THE GPS COORDS WE WILL NEED TO EXPLODE THE STRING. THEY ARE INSERTED AS A STRING TO THE DATABASE*/

                if (validateGPSCoords($GPSCoords) == false) {
                    $message = 'GPS Coords of that Address invalid';
                    $inputs_are_valid = false;
                }

                if ($inputs_are_valid) {
                    if (updatePlaceInfo($placeID, $title, $desc, $address, $GPSCoords, $locationID, $numRooms, $numBathrooms, $capacity) != true) {
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
                }
            }
        }
    }
}
echo json_encode(array('message' => $message));
