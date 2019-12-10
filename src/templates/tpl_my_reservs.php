<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');

function draw_my_reservs_body($user_info, $myReservations) { ?>
    <main id="my-container">
    <?php draw_my_reservs_header($user_info);
          list_houses($myReservations, "My_Reservs", $user_info['userID']); ?>
    </main>
<?php } 

function draw_my_reservs_header($user_info) { ?>
    <h3>My Reservations</h3>
    <section id="my-header" class="row">
        <div id="my-user-info" class="row">
            <div class="row">
                <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=$user_info['userID']?>">
                    <img class="circular-img" src="../assets/images/users/small/<?=$user_info['image']?>">
                </a>
                <p><?=$user_info['name']?></p>
            </div>
        </div>
    </section>
<?php } ?>