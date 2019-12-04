
<?php
if(!(isset($_SESSION['userID']))){
    // TODO// AFTER LOGIN IMPLEMENTED CONTINUE
    //header("Location: main_page.php");
    //die("UserId not set on my houses");
}else{
    $_SESSION['userID']=3;
}

include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');


function draw_my_houses_base_head(){ ?>
    <div id="my_houses_container">
<?php } ?>

<?php
function draw_my_houses_statistics(){ 
    $_SESSION['userID']=2;
    $userID=$_SESSION['userID'];  
?>

    <article id="my_houses_statistics_container">
        
        <header id="my_houses_statistics_header">
            <p>My Houses</p>
        </header>

        <section id="my_houses_statistics_info">
            <img class="circular-img" id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
            <p id="my_houses_statistics_owner"><?= getUserInformation($userID)['name'];?></p>
            <i class="fas fa-chart-line"></i>
            <p>Number of Reservations: <?=getUserNumberofReservations($userID)['cnt']; ?></p>

        </section>

    </article>
    
    <aside id="my_houses_add_new">

        <i class="fas fa-plus"></i>
        <a>Add</a>

    </aside>

<?php } ?>


<?php

function draw_my_houses_item_list(){ 
    
    $userID=$_SESSION['userID'];

    $array_places=getUserPlaces($userID);

?>
    <section id="my_houses_list_container">
<?php

    for($i = 0; $i < count($array_places); $i++)
        draw_horizontal_card($i + 0.5,true,false,$array_places[$i]['placeID']);
?>

    </section>
<?php } 

function draw_my_houses_base_end(){ ?>
    </div>
<?php } ?>

