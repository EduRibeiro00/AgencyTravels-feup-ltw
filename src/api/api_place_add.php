<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_location.php');
include_once('../includes/img_upload.php');

const true_message = 'true';

function check_File_Integrity($imageName, $array_fileNames)
{
    return in_array($imageName, $array_fileNames);
}

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

    $images = $_FILES['imagePlaceFile'];
    //CREATE AN ARRAY TO STORE ALL THE VALID IMAGES UPLOADED
    $images_uploaded_valid = array();
    $num_images_uploaded_valid = 0;

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

    if ($num_images_uploaded_valid < 1 || $num_images_uploaded_valid > 6) {
        $message = 'You cannot create an house with that number of pictures';
    } else {


        //IF THE ERROR MESSAGE WAS NOT TRIGGERED, CONTINUE
        if (strcmp($message, true_message) == 0) {

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
            } else {
                $message = 'Parameters not validated';
            }
        }
    }
}
echo json_encode(array('message' => $message));
