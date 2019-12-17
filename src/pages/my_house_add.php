<?php
include_once('../includes/session_include.php');
include_once('../includes/input_validation.php');
include_once('../database/db_user.php');
include_once('../database/db_location.php');

if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js','../js/place_add.js','../js/googleMapsHouseForm.js'];
} else {
    die(header('Location: ../pages/index.php'));
}

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_house_form.php');

draw_head($jsFiles);
draw_navbar($user_info, false);
$all_locations = getAllLocations();
draw_form(null, false, $all_locations);

draw_footer();

?>
