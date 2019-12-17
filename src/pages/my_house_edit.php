<?php
include_once('../includes/session_include.php');
include_once('../database/db_user.php');
include_once('../includes/input_validation.php');

if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false && isset($_GET['placeID']) && validatePosIntValue($_GET['placeID'])) {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js', '../js/place_edit.js','../js/googleMapsHouseForm.js'];
} else {
    die(header('Location: ../pages/index.php'));
}


$userID = $_SESSION['userID'];
$placeId = $_GET['placeID'];


$array_places = getUserPlaces($userID);

$counter_matchs = -1;
//Verifies if that house belongs to the current owner login
foreach ($array_places as $place) {
    if ($place['placeID'] == $placeId) {
        $counter_matchs = 1;
        break;
    }
}

if ($counter_matchs == -1) {
    // TODO: AFTER LOGIN IMPLEMENTED CONTINUE
    die(header("Location: ../pages/index.php"));
}

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_house_form.php');

draw_head($jsFiles);
draw_navbar($user_info, false); 
$all_locations = getAllLocations();

draw_form($place, true, $all_locations);
draw_footer();
