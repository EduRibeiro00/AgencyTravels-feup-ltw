<?php
    include_once('../includes/session_include.php');
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_comment.php');
    include_once('../templates/tpl_place.php');
    include_once('../templates/tpl_similar_offer.php');
    include_once('../templates/tpl_availability.php');
    include_once('../database/db_myplace.php');
	draw_head(['../js/main.js']);
    draw_navbar();
    
    $place_id=$_GET['place_id'];

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

    //Adress string parsing

    $house_address_full=$house_address.",".$house_address_city.",".$house_address_country;
    
    //Draw Section
    draw_myplace_slideshow();
    first_line();
  
    draw_my_place_sidebar($house_rating); 
    draw_my_place_icon_desc($house_name,$house_numRooms,$house_capacity,$house_numBathrooms,$house_description);
    draw_my_place_location($house_address_full,$house_gpsCoords);

    draw_avaiability_block();
    //House Rating is the avg rating of the house
    draw_all_comments($house_rating,$house_comments); 
    draw_similar_offer_slide_show();  
    last_line();
    draw_footer(); 
?>
