<?php     
    include_once('../includes/session_include.php');
    include_once('../includes/input_validation.php');
    include_once('../database/db_user.php');

    if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
        $user_info = getUserInformation($_SESSION['userID']);
        $userID = $_SESSION['userID'];
        $jsFiles = ['../js/main.js', '../js/cancel_reserv.js'];
    }
    else{
        die(header('Location: ../pages/index.php'));    
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_my_reservs.php');

    $myReservations = getUserReservations($userID);
    for($i = 0; $i < count($myReservations); $i++) {
        $myReservations[$i]['price'] = getAveragePrice($myReservations[$i]['placeID']);
        $myReservations[$i]['nVotes'] = getPlaceNumVotes($myReservations[$i]['placeID']);
    }

    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_my_reservs_body($user_info, $myReservations);  
    draw_footer();
?>