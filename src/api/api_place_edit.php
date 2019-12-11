<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/img_upload.php');

function find_photo_in_database_array($photo_hash, $images_place_from_database, $images_place_from_database_len)
{

    for ($i = 0; $i < $images_place_from_database_len; $i++) {
        if (strcmp($photo_hash, $images_place_from_database[$i]['image']) == 0) {
            return true;
        }
    }
    return false;
}


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
    $images = $_FILES['imagePlaceFile']['tmp_name'];
    $capacity = $_POST['capacity'];

    $photosToRemove_str = $_POST['imagesToRemoveArray'];

    //Check if there is any photo to remove
    if (strlen($photosToRemove_str) != 0) {
        $photosToRemove = explode(",", $photosToRemove_str);
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
            if (find_photo_in_database_array($photosToRemove[$i], $images_place_from_database, count($images_place_from_database)) === false) {
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

            if ($images[$i] != "") {

                if (!checkIfImageIsValid($images[$i])) {
                    $message = 'invalid image';
                    break;
                }
            } else {
                //IF ITS A "" ENTRY ON THE ARRAY WE DECREMENT THE NUMBER OF ELEMENTS
                $total--;
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

                if (strcmp(updatePlaceInfo($placeID, $title, $desc, $address, $city, $country, $numRooms, $numBathrooms, $capacity), true_message) != 0) {
                    $message = 'Error Updating home';
                } else {
                    if (strcmp($message, true_message) == 0) {
                        //NEW PHOTOS UPLOADED
                        for ($i = 0; $i < $total; $i++) {
                            if (uploadPlaceImage($placeID, $images[$i]) != true) {
                                $message = 'Invalid IMAGE';
                                break;
                            }
                        }

                        for($j=0;$j<$num_photos_to_remove;$j++){


                            
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
