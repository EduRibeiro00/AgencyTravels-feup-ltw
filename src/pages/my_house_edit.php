<?php

    if(!(isset($_SESSION['userID']))){
        // TODO// AFTER LOGIN IMPLEMENTED CONTINUE
        //header("Location: main_page.php");
        //die("UserId not set on my houses");
    }else{
        $_SESSION['userID']=1;
    }
    $_SESSION['userID']=2;
    
    $userID=$_SESSION['userID'];
    $placeId=$_GET['placeID'];
    
    include_once('../database/db_user.php');

    $array_places=getUserPlaces($userID);

    $couter_matchs=-1;
    
    //Verifies if that house belongs to the current owner login
    foreach($array_places as $place){
        if($place['placeID']==$placeId){
            $couter_matchs=1;
            break;
        } 
    }
    if($couter_matchs==-1){
         // TODO// AFTER LOGIN IMPLEMENTED CONTINUE
         header("Location: main_page.php");
         die("Dont Have permissions");
    }


    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_house_edit_form.php');

    draw_head(['../js/main.js','../js/place_edit.js']);
    
    // TODO: meter a user_info como 1o argumento
    draw_navbar(false);
    

?>




<div id="my_house_edit_container">

    <h2>My House Edit</h2>
    
    <?php draw_form($placeId); ?>

</div>

<?php
    draw_footer();
?>