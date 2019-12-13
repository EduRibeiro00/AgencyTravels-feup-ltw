<?php
include_once('../includes/session_include.php');
include_once('../database/db_user.php');
include_once('../database/db_location.php');

if (isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js','../js/place_add.js'];
} else {
    die(header('Location: ../pages/initial_page.php'));
}

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_house_form.php');

$userID = $_SESSION['userID'];


draw_head($jsFiles);
draw_navbar($user_info, false);
$all_locations = getAllLocations();

draw_form(null,false,$all_locations);

draw_footer();

?>
