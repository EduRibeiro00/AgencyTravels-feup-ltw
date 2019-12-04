<?php 
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_my_houses.php');
    draw_head(['../js/main.js','../js/house_edit.js']);
    


    $_SESSION['userID']=1;
/*
    if(!(isset($_SESSION['userID']))){
        /* TODO// AFTER LOGIN IMPLEMENTED CONTINUE
        //header("Location: main_page.php");
        //die("UserId not set on my houses");
    }else{
    }
    */
    draw_navbar();
    draw_my_houses_base_head();
    draw_my_houses_statistics();
    draw_my_houses_item_list();
    draw_my_houses_base_end();
    draw_footer();
?>






