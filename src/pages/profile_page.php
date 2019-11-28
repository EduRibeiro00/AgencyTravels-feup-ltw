<?php
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_page.php');
    include_once('../database/db_places.php');
    include_once('../database/db_user.php');

    $userID = $_GET['userID'];

    $user_info = getUserInformation($userID);
    $city_image = getRandomImagesFromCity($user_info['locationID'], 1);
    $user_places = getUserPlaces($userID);

    foreach($user_places as $k => $place) {
        $user_places[$k]['avg_price'] = getAveragePrice($place['placeID'])['avg_price'];
    }

    draw_head(['../js/main.js']);
    draw_navbar();
    draw_profile_info($user_info, $user_places, $city_image);
    draw_footer();
?>