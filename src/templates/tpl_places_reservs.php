<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');

function draw_places_reservs_body($place_reservations, $user_info) { ?>
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
                    <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=$user_info['userID']?>">
                        <img class="circular-img" src="../assets/images/users/small/<?=$user_info['image']?>">
                    </a>
                    <p><?=$user_info['name']?></p>
                </div>
            </div>
        </section>
    </header>
<?php } 

function list_place_reservs($place_reservations) { ?>
    <section>
        <?php
        if(empty($place_reservations)) { ?>
            <em>There are no reservations for your places</em>
        <?php } 
        else { ?>
            <section id="current-reservs">
                <h4>Current reservations</h4>
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['startDate'] < date('Y-m-d') && $place_reservation['endDate'] > date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section>
            <section id="future-reservs">
                <h4>Future reservations</h4>
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['startDate'] > date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section>
            <section id="previous-reservs">
                <h4>Previous reservations</h4>
                <?php foreach($place_reservations as $place_reservation) {
                    if($place_reservation['endDate'] < date('Y-m-d')) {
                        place_reservs_card($place_reservation);
                    }
                } ?>
            </section> 
        <?php } ?>
    </section>
<?php } ?> 

<?php function place_reservs_card($place_reservation) { ?>
    <article class="place-reserv-card">
        <section class="client-info row">
            <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=$place_reservation['userID']?>">
                <img class="circular-img" src="../assets/images/users/small/<?=$place_reservation['image']?>">
                <p><?=$place_reservation['username']?></p>
            </a>
        </section>
        <section class="reserv-info">
            <div class="reserv-place-info">
                <p>Reservation for:</p>
                <p><?=$place_reservation['title']?></p>
                <p><?=$place_reservation['address']?></p>
            </div>
            <div class="reserv-price row">
                <p>Total price:</p>
                <p><?=$place_reservation['price']?>€</p>
            </div>
        </section>
        <section class="row place-reserv-dates">
				<div>
					<p>Reservation from:</p>
					<p><?=$place_reservation['startDate']?></p>
		 		</div>
				<div>
				 	<p>Until:</p>
					<p><?=$place_reservation['endDate']?></p>
		 		</div>
        </section>
        <section class="column card-options">
            <?php if(canCancelReservation($place_reservation['startDate'])) { ?>
                    <a class="button cancel-button" href="#" data-id="<?=$place_reservation['reservationID']?>"> 
                        Cancel
                    </a>
            <?php } ?>
        </section>
    </article> 
<?php } ?>



