<?php
    include_once('../includes/session_include.php');
    
    if(isset($_SESSION['userID'])) {
        die(header('Location: ../pages/profile_page.php?userID=' . $_SESSION['userID']));
    }
    else {
        $jsFiles = ['../js/main.js', '../js/profile_form.js', '../js/login.js', '../js/signup.js'];
    }

    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_form.php');
    include_once('../database/db_places.php');

    $all_locations = getAllLocations();

    draw_head($jsFiles);
    draw_navbar(NULL);
    draw_profile_form($all_locations, 'Signup');
    draw_footer();
?>