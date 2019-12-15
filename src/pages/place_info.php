<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
    include_once('../database/db_places.php');    
    include_once('../includes/input_validation.php');

	if(!(isset($_GET['place_id']) && validateIntValue($_GET['place_id']))) {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    $placeID = $_GET['place_id'];
    $place = getPlace($placeID);
    
    if($place === false) {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    if(isset($_SESSION['userID']) && validateIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/comments.js', '../js/googleMapsMyPlace.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/login.js', '../js/googleMapsMyPlace.js'];
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_slideshow.php');
    include_once('../templates/tpl_place.php');
    
	draw_head($jsFiles);
	draw_navbar($user_info, false);
	
    $houseComments = getHouseComments($placeID);
    for($i = 0; $i < count($houseComments); $i++) {
        $houseComments[$i]['replies'] = getRepliesForReview($houseComments[$i]['reviewID']);
    }

    $housePrice = getAveragePrice($placeID);
    $houseOwnerInfo = getUserInformation($place['ownerID']);

   
	draw_slideshow($place['images']);
		
	//Draw Section
	draw_place_info_body($place, $houseComments, $houseOwnerInfo, $housePrice);
    draw_footer(); 
?>
