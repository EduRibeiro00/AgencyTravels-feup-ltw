
<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');

function draw_my_houses_base_head(){ ?>
    <div id="my_houses_container">
<?php } ?>

<?php
function draw_my_houses_statistics(){ ?>

    <article id="my_houses_statistics_container">
        
        <header id="my_houses_statistics_header">
            <p>My Houses</p>
        </header>

        <section id="my_houses_statistics_info">
            <img class="circular-img" id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
            <p id="my_houses_statistics_owner">Ruben Almeida</p>
            <i class="fas fa-chart-line"></i>
            <p>Number of Reservations:3</p>

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

