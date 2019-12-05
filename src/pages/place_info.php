<?php
    include_once('../includes/session_include.php');
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_comment.php');
    include_once('../templates/tpl_place.php');
    include_once('../templates/tpl_similar_offer.php');
    include_once('../templates/tpl_availability.php');
    include_once('../database/db_myplace.php');
    include_once('../database/db_user.php');
    include_once('../database/db_places.php');

    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/place_page.js','../js/price_reservation.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/login.js','../js/price_reservation.js'];
    }

    
	draw_head($jsFiles);
    draw_navbar($user_info);
    
    $place_id = $_GET['place_id'];
    
    $house_name=get_house_title($place_id)["title"];
    $house_rating=get_house_rating($place_id)["rating"];
    $house_description=get_house_description($place_id)["description"];
    $house_numRooms=get_house_numRooms($place_id)["numRooms"];
    $house_capacity=get_house_capacity($place_id)["capacity"];
    $house_numBathrooms=get_house_numBathrooms($place_id)["numBathrooms"];
    $house_address=get_house_address($place_id)["address"];
    $house_address_city=get_house_address_city($place_id)["city"];
    $house_address_country=get_house_address_country($place_id)["country"];
    $house_gpsCoords=get_house_gpsCoords($place_id)["gpsCoords"];
    $house_comments=get_house_comments($place_id);
    $house_avg_price=get_avg_price($place_id)["avg"];
    $house_owner_name=getPlaceOwnerName($place_id)["name"];

    //Adress string parsing

    $house_address_full=$house_address.",".$house_address_city.",".$house_address_country;
    
    //Draw Section
    draw_myplace_slideshow();
    first_line();
  
    draw_my_place_sidebar($house_avg_price,$house_rating,$house_owner_name,$place_id); 
    draw_my_place_icon_desc($house_name,$house_numRooms,$house_capacity,$house_numBathrooms,$house_description);
    draw_my_place_location($house_address_full,$house_gpsCoords);

    draw_avaiability_block();
    //House Rating is the avg rating of the house
    draw_all_comments($house_rating,$house_comments); 
    draw_similar_offer_slide_show();  
    last_line();
    draw_footer(); 
?>
