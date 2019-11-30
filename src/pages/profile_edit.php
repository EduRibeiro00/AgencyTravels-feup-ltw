<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js', '../js/profile_form.js', '../js/profile_edit.js'];
    }
    else {
        die(header('Location: ../pages/initial_page.php'));
    }
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_form.php');


    draw_head($jsFiles);
    draw_navbar($user_info);
    draw_profile_form('Edit profile', $user_info);
    draw_footer();
?>