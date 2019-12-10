<?php     
    include_once('../includes/session_include.php');
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_my_reservs.php');

    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $userID = $_SESSION['userID'];
        $jsFiles = ['../js/main.js'];
    }
    else{
        die(header('Location: ../pages/initial_page.php'));    
    }
    
    $myReservations = getUserReservations($userID);
    for($i = 0; $i < count($myReservations); $i++) {
        $myReservations[$i]['price'] = getAveragePrice($myReservations[$i]['placeID'])['avg_price'];
        $myReservations[$i]['nVotes'] = getPlaceNumVotes($myReservations[$i]['placeID']);
    }

    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_my_reservs_body($user_info, $myReservations);  
    draw_footer();
?>