<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../database/db_places.php');
    
    if(!isset($_GET['place_id'])) {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    $placeID = $_GET['place_id'];
    $place = getPlace($placeID);

    if($place === false) {
        die(header('Location: ../pages/initial_page.php'));
    }

    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_slideshow.php');
    include_once('../templates/tpl_comment.php');
    include_once('../templates/tpl_place.php');
    include_once('../templates/tpl_similar_offer.php');
    include_once('../templates/tpl_availability.php');
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/place_page.js','../js/price_reservation.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/login.js','../js/price_reservation.js'];
    }

    
	draw_head($jsFiles);
	draw_navbar($user_info, false);
	

    $house_comments = getHouseComments($placeID);
    $housePrice = getAveragePrice($placeID)['avg_price'];
    $house_owner_info = getUserInformation($place['ownerID']);

    //Adress string parsing
    $house_address_full = $place['address'] . ", " . $place['city'] . ", " . $place['country'];
   
    //Draw Section
    draw_slideshow($place['images']);
    first_line();
  
    draw_my_place_sidebar($housePrice, $place['rating'], $house_owner_info, $placeID); 
    draw_my_place_icon_desc($place['title'], $place['numRooms'], $place['capacity'], $place['numBathrooms'], $place['description']);
    draw_my_place_location($house_address_full, $place['gpsCoords']);

    draw_availability_block();
    //House Rating is the avg rating of the house
    draw_all_comments($place['rating'], $house_comments); 
    draw_similar_offer_slide_show();  
    last_line();
    draw_footer(); 
?>
