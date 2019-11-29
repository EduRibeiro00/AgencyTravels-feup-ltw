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

    draw_head($jsFiles);
    draw_navbar(null);
    draw_profile_form('Signup');
    draw_footer();
?>