<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/input_validation.php');

    if(!(isset($_GET['userID']) && validatePosIntValue($_GET['userID']))) {
        die(header('Location: ../pages/index.php'));
    }
    
    $userID = $_GET['userID'];
    $profile_user_info = getUserInformation($userID);
    if($profile_user_info === false) {
        die(header('Location: ../pages/index.php'));
    }
    
    if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js','../js/googleMapsProfile.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/login.js', '../js/googleMapsProfile.js'];
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_page.php');
    include_once('../database/db_places.php');

    $city_images = getRandomImagesFromCity($profile_user_info['locationID'], 1);
    if(!empty($city_images)) {
        $city_image = "../assets/images/places/big/" . $city_images[0]['image'];
    }
    else {
        $city_image = "../assets/images/others/standard-background.jpeg";
    }


    $user_places = getUserPlaces($userID);
    $user_place_comments = getReviewsForUserPlaces($userID, 5);

    foreach($user_places as $k => $place) {
        $user_places[$k]['avg_price'] = getAveragePrice($place['placeID']);
    }

    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_profile_info($profile_user_info, $user_places, $city_image, $user_place_comments);
    draw_footer();
?>