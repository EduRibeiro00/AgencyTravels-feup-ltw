<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../database/db_location.php');
include_once('../includes/img_upload.php');

$message='welcome';

if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
    $message = 'user not logged in';
} else {
    $ownerID = $_POST['userID'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $numRooms = $_POST['numRooms'];
    $numBathrooms = $_POST['numBathrooms'];
    $capacity = $_POST['capacity'];

    $files_array = $_FILES['imagePlaceFile'];
    //Going to iterate all images and parse just the valid ones
    $files_array_length=count($files_array);

    $i=0;

    foreach ($_FILES['imagePlaceFile']['name'] as $filename){ 
        if (!checkIfImageIsValid($_FILES['imagePlaceFile']['tmp_name'][$i])) {
            $message = 'invalid image';
        break;
    }
    $i=$i+1;
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
                    return 'Error while inserting location new';
                }
                $locationID = locationGetID($city, $country)['locationID'];
            } else {
                $locationID = $array_locations['locationID'];
            }

            if (is_null($locationID)) {
                $message = 'Location ID NULL';
            } else {
                $message = newPlace($title, $desc, $address, $locationID, $numRooms, $numBathrooms, $capacity, $ownerID);
            }

            if(strcmp($message,'true')==0){
                
                //GET THE NEW PLACE ID
                $placeID=getPlaceID($title,$address,$ownerID)['placeID'];


                for($j=0;$j<$files_array_length;$j++){
                    if(uploadPlaceImage($placeID,$files_array[$j])!=true){
                        $message='Invalid IMAGE';
                        break;
                    }
                }
            }

        } else {
            $message = 'Parameters not validated';
        }
    }
}
echo json_encode(array('message' => $message));
