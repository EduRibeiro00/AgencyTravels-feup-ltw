<?php     
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/input_validation.php');

    if(!(isset($_GET['userID']) && validatePosIntValue($_GET['userID']))) {
        die(header('Location: ../pages/index.php'));
    }
    
    $houseOwnerID =  $_GET['userID'];
    $houseOwnerInfo = getUserInformation($houseOwnerID);
    if($houseOwnerInfo === false) {
        die(header('Location: ../pages/index.php'));
    }
    
    if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
        $user_info = getUserInformation($_SESSION['userID']);
        $userID = $_SESSION['userID'];
        $jsFiles = ['../js/main.js', '../js/delete_place.js', '../js/availability_add.js'];
    }
    else {
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/login.js'];
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_my_houses.php');

    $myHouses = getUserPlaces($houseOwnerID);
    
    for($i = 0; $i < count($myHouses); $i++) {
        $myHouses[$i]['price'] = getAveragePrice($myHouses[$i]['placeID']);
        $myHouses[$i]['nVotes'] = getPlaceNumVotes($myHouses[$i]['placeID']);
    }

    $numReservs = getUserPlacesNumberofReservations($houseOwnerID);

    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_my_houses_body($houseOwnerInfo, $myHouses, $numReservs);  
    draw_footer();
?>






