<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');


function draw_my_houses_base_head()
{ ?>
    <div id="my_houses_container">
    <?php } ?>

    <?php
    function draw_my_houses_statistics($userID)
    { ?>

        <article id="my_houses_statistics_container">

            <header id="my_houses_statistics_header">
                <p>My Houses</p>
            </header>

            <section id="my_houses_statistics_info">
                <img class="circular-img" id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
                <p id="my_houses_statistics_owner"><?= getUserInformation($userID)['name']; ?></p>
                <i class="fas fa-chart-line"></i>
                <p>Number of Reservations: <?= getUserNumberofReservations($userID)['cnt']; ?></p>

            </section>

        </article>

        <aside id="my_houses_add_new">

            <i class="fas fa-plus"></i>
            <a>Add</a>

        </aside>

    <?php } ?>


    <?php

    function draw_my_houses_item_list($userID)
    {
        $array_places = getUserPlaces($userID);
        ?>

        <section id="my_houses_list_container">
            <?php
                list_houses_result($array_places,true);
            ?>

        </section>
    <?php }

function draw_my_houses_base_end() { ?>
    </div>
<?php } ?>