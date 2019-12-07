<?php 
    
    include_once('../includes/session_include.php');
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_my_houses.php');
    
    
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
        $userID=$_SESSION['userID'];
        $user_info = getUserInformation($_SESSION['userID']);
        $jsFiles = ['../js/main.js'];
    }
    else{
        $user_info = NULL;
        $jsFiles = ['../js/main.js', '../js/login.js'];
    }
    
    draw_head($jsFiles);
    // TODO: meter a user_info como 1o argumento
    draw_navbar($user_info,false);
    draw_my_houses_base_head();
    draw_my_houses_statistics($userID);
    draw_my_houses_item_list($userID);
    draw_my_houses_base_end();  
    draw_footer();
?>






