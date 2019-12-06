<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    
    $userID = $_GET['userID'];
    
    $profile_user_info = getUserInformation($userID);
    if($profile_user_info === false) {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/login.js'];
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_page.php');
    include_once('../database/db_places.php');

    $city_image = getRandomImagesFromCity($profile_user_info['locationID'], 1);
    $user_places = getUserPlaces($userID);
    $user_place_comments = getReviewsForUserPlaces($userID, 5);

    foreach($user_places as $k => $place) {
        $user_places[$k]['avg_price'] = getAveragePrice($place['placeID'])['avg_price'];
    }

    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_profile_info($profile_user_info, $user_places, $city_image, $user_place_comments);
    draw_footer();
?>