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
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/comments.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/place_page.js', '../js/login.js'];
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_slideshow.php');
    include_once('../templates/tpl_place.php');
    
	draw_head($jsFiles);
	draw_navbar($user_info, false);
	
	$place = getPlace($placeID);
    $houseComments = getHouseComments($placeID);
    for($i = 0; $i < count($houseComments); $i++) {
        $houseComments[$i]['replies'] = getRepliesForReview($houseComments[$i]['reviewID']);
    }

    $housePrice = getAveragePrice($placeID);
    $houseOwnerInfo = getUserInformation($place['ownerID']);

   
	draw_main_slideshow($place['images']);
		
	//Draw Section
	draw_place_info_body($place, $houseComments, $houseOwnerInfo, $housePrice);
    draw_footer(); 
?>
