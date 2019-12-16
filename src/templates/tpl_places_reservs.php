<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');
include_once('../templates/tpl_cards.php');
include_once('../templates/tpl_common.php');

function draw_places_reservs_body($place_reservations, $user_info) { 
    draw_confirmation_form();
    ?>
    <main id="my-places-reservs">
    <?php draw_places_reservs_header($user_info);
          list_place_reservs($place_reservations); ?>
    </main>
<?php }

function draw_places_reservs_header($user_info) { ?>
    <header>
        <h3>All reservations for my places</h3>
        <section id="my-header" class="row">
            <div id="my-info" class="row">
                <div class="row">
                    <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=htmlspecialchars($user_info['userID'])?>">
                        <img class="circular-img" src="../assets/images/users/small/<?=htmlspecialchars($user_info['image'])?>">
                    </a>
                    <p><?=htmlspecialchars($user_info['name'])?></p>
                </div>
            </div>
        </section>
    </header>
<?php } 

function list_place_reservs($place_reservations) { ?>
    <section id="place-reservs-listing">
        <?php
        if(empty($place_reservations)) { ?>
            <em>There are no reservations for your places</em>
        <?php } 
        else { ?>
            <h4>Current reservations</h4>
            <section id="current-reservs" class="row">
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['startDate'] < date('Y-m-d') && $place_reservation['endDate'] > date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section>
            <h4>Future reservations</h4>
            <section id="future-reservs" class="row">
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['startDate'] > date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section>
            <h4>Previous reservations</h4>
            <section id="previous-reservs" class="row">
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['endDate'] < date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section> 
        <?php } ?>
    </section>
<?php } ?>



