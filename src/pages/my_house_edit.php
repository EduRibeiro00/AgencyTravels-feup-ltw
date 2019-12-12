<?php
include_once('../includes/session_include.php');
include_once('../database/db_user.php');
include_once('../templates/tpl_common.php');
include_once('../templates/tpl_house_form.php');

if (isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js', '../js/place_edit.js'];
} else {
    die(header('Location: ../pages/initial_page.php'));
}


$userID = $_SESSION['userID'];
$placeId = $_GET['placeID'];


$array_places = getUserPlaces($userID);

$couter_matchs = -1;
//Verifies if that house belongs to the current owner login
foreach ($array_places as $place) {
    if ($place['placeID'] == $placeId) {
        $couter_matchs = 1;
        break;
    }
}
if ($couter_matchs == -1) {
    // TODO// AFTER LOGIN IMPLEMENTED CONTINUE
    var_dump($array_places);
    die(header("Location: ../pages/initial_page.php"));
}

draw_head($jsFiles);
draw_navbar($user_info, false); ?>

<div id="my_house_edit_container">

    <h2>My House Edit</h2>

    <?php draw_form($place, true); ?>

</div>

<?php
draw_footer();
?>