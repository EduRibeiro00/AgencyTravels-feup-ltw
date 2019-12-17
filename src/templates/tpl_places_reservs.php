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
    <?php draw_places_reservs_header($user_info);
          list_place_reservs($place_reservations); ?>
<?php }

function draw_places_reservs_header($user_info) { ?>
	<header id="my-header">
		<h3>All reservations for my places</h3>
		<div class="row">
			<a class="user_img" href="../pages/profile_page.php?userID=<?=$user_info['userID']?>">
				<img class="circular-img" src="../assets/images/users/small/<?=$user_info['image']?>">
			</a>
			<a class="user_username" href="../pages/profile_page.php?userID=<?=$houseOwnerInfo['userID']?>">
				<?=htmlspecialchars($user_info['name'])?>
			</a>
		</div>
    </header>
<?php } 

function list_place_reservs($place_reservations) { ?>
    <main id="place-reservs-listing">
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
		</main>
<?php } ?>



