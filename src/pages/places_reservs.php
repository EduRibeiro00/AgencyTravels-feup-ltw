<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/cancel_reserv.js'];
    }
    else {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_places_reservs.php');
    include_once('../database/db_places.php');

    $place_reservations = getReservationsForOwner($_SESSION['userID']);
    draw_head($jsFiles);
    draw_navbar($user_info, false);
    draw_places_reservs_body($place_reservations, $user_info); 
    draw_footer();
?>