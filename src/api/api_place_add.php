<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_location.php');
include_once('../includes/img_upload.php');
include_once('../includes/place_forms.php');

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
            
            if (check_File_Integrity($images['name'][$i], $array_fileNames) == true) {
                
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
					// TODO: fazer maneira menos enrabada
					$placeID = newPlace($title, $desc, $address, $locationID, $numRooms, $numBathrooms, $capacity, $ownerID);
                    if ($placeID != false) {
                        //GET THE NEW PLACE ID
                        //$placeID =  //getPlaceID($title, $address, $ownerID)['placeID'];

                        for ($i = 0; $i < $num_images_uploaded_valid; $i++) {
                            if (uploadPlaceImage($placeID, $images_uploaded_valid[$i]) != true) {
                                $message = 'Invalid IMAGE';
                                break;
                            }
                        }
                    } else {
                        $message = $placeID;
                    }
                }
            } else {
                $message = 'Parameters not validated';
            }
        }
    }
}
echo json_encode(array('message' => $message));
