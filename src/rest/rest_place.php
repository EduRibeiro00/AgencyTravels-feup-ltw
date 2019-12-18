<?php
header("Content-Type: application/json");

include_once('../includes/session_include.php');
include_once('../database/db_user.php');
include_once('../includes/place_forms.php');
include_once('../includes/img_upload.php');
include_once('../includes/input_validation.php');
include_once('../database/db_places.php');


switch($_SERVER['REQUEST_METHOD']) {

    case 'DELETE':
        return placeDeleteRequest();

    case 'POST':
        placePostRequest();
        break;

    // ao executar uma request, o valor de
    // retorno e sempre 200, o que leva ao mau
    // funcionamento do pedido. Tal se pode dever
    // a utilizacao do FormData no place_add.js, ou
    // outra razao nao identificada. Decidimos mesmo assim,
    // nao apagar o codigo feito para os pedidos DELETE e POST.
    // A nossa intencao era fazer uma REST API para as casas,
    // podendo ser feitos pedidos POST, GET, DELETE e PUT, para
    // alterar, acrescentar, eliminar casas, etc.

    default:
        break;
}


function placeDeleteRequest() {
    if ($_SESSION['csrf'] !== $_GET['csrf']) {
        response("403 Forbidden");
        return;
	}

    $placeID = $_GET['placeID'];

    if(validatePosIntValue($placeID)) {

        $place = getPlace($placeID);
        if($place['ownerID'] != $_SESSION['userID']){
            header("WWW-Authenticate: Basic");
            response("401 Unauthorized");
            return;
        }

        deletePlaceAllImages($placeID);
        $message = deletePlace($placeID) ? "204 No Content" : "500 Internal Server Error";
        response($message);
        return;
    }
    else {
        response("400 Bad Request");
        return;
    }
}


const true_message = 'true';
function placePostRequest() {

    if($_SERVER['HTTP_ACCEPT'] != 'application/json') {
        response("406 Not Acceptable");
        return;
    }

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        response("403 Forbidden");
        return;
    }

    if (!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
        header("WWW-Authenticate: Basic");
        response("401 Unauthorized");
        return;

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

        //Retrieve the 6 possible file to add
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
                if (!validateAddressValue($address)) {
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

                if($inputs_are_valid) {
                    $placeID = newPlace($title, $desc, $address, $GPSCoords, $locationID, $numRooms, $numBathrooms, $capacity, $ownerID);

                    if ($placeID != false) {

                        for ($i = 0; $i < $num_images_uploaded_valid; $i++) {
                            if (uploadPlaceImage($placeID, $images_uploaded_valid[$i]) != true) {
                                $message = 'Invalid IMAGE';
                                break;
                            }
                        }
                    } else {
                        $message = 'Fail create new place';
                    }
                }
            }
        }

        if ($Duplicates == true) {
            $message = 'Duplicate Images';
        }
    }

    if($message == true_message || $message == 'Duplicate Images') {
        response("201 Created", array('message' => $message));
        return;
    }
    else {
        response("400 Bad Request", array('message' => $message));
        return;
    }
}


function response($status, $responseArray = null) {
	header("HTTP/1.1 " . $status);
    
    if($responseArray != null) {
        $json_response = json_encode($responseArray);
        echo $json_response;
    }
}