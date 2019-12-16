<?php 
include_once('../templates/tpl_common.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');
include_once('../templates/tpl_place.php');
include_once('../templates/tpl_slideshow.php');

function draw_horizontal_card($place, $drawingOption, $userID) { ?>
	<article class="row card">
	<div class="row">
		<?php draw_hcard_slideshow($place['images'], $place['placeID']); ?>
		<a class="column info" href="../pages/place_info.php?place_id=<?=$place['placeID']?>">
			<h4><?=$place['title']?></h4>
			<?php draw_place_details($place['numRooms'], $place['capacity'], $place['numBathrooms']); ?>
			<footer class="row">
				<?php if(isset($place['price']) && $place['price'] != null) { ?>
					<p><?=$place['price']?>€ / night</p>
				<?php }
				else { ?>
					<p><em>No prices available</em></p>
				<?php } ?>
				<div class="card-rating">
					<?php if(isset($place['nVotes']) && $place['nVotes'] != null && $place['nVotes'] != 0) { 
						draw_star_rating($place['rating']); ?>
						<strong>(<?=$place['nVotes']?>)</strong>
					<?php }
					else { ?>
						<em>No rating available</em>
					<?php } ?>
				</div>
			</footer>
		</a>
	</div>


<?php 	if($drawingOption == 'My_Houses' && isset($_SESSION['userID']) && $_SESSION['userID'] == $userID) {  ?>
			<section class="column">
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Edit
				</a>

				<a class="button add-avail" href="#" data-id="<?=$place['placeID']?>">
					Availabilites
				</a>
			
				<a class="button remove-button" href="#" data-id="<?=$place['placeID']?>">
					Remove
				</a>

			</section>
   <?php } 
		 else if($drawingOption == "My_Reservs" && isset($_SESSION['userID']) && $_SESSION['userID'] == $userID) { ?>
			<section class="column reserv-dates">
				<div>
					<p>From:</p>
					<p><?=$place['startDate']?></p>
		 		</div>
				<div>
				 	<p>Until:</p>
					<p><?=$place['endDate']?></p>
		 		</div>
		 	</section>

			<section class="column">

				<?php  
					if(canCancelReservation($place['startDate'])) { ?>
						<a class="button cancel-button" href="#" data-id="<?=$place['reservationID']?>"> 
							Cancel
						</a>
			   <?php } 
			
					 if(canReviewPlace($place['endDate'], $place['reviewID'])) { ?>
						<a class="button" href="place_info.php?place_id=<?=$place['placeID']?>#add-review-section"> 
							Review place
						</a> 
					<?php } ?>
			</section>
		<?php } ?>
	
	</article>
<?php }


function draw_user_card($user, $drawingOption = null) { ?>
	<section class="user_card">
		<a class="user_img" href="../pages/profile_page.php?userID=<?=$user['userID']?>">
			<img class="circular-img" src="../assets/images/users/small/<?=$user['image']?>">
		</a>
		<a class="user_username" href="../pages/profile_page.php?userID=<?=$user['userID']?>">
			<?=$user['username']?>
		</a>
		<?php if($drawingOption == 'email') {?>
			<a class="user_contact" href="mailto: <?=$user['email']?>">Speak with the Owner</a>
		<?php } else if($drawingOption == 'rating') draw_star_rating($user['stars']) ?>

	</section>

<?php } ?>

<?php function place_reservs_card($place_reservation) { ?>
    <article class="place-reserv-card column">
            <section class="client-info row">
                <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=$place_reservation['userID']?>">
                    <img class="circular-img" src="../assets/images/users/small/<?=$place_reservation['image']?>">
                    <p><?=$place_reservation['username']?></p>
                </a>
            </section>
            <section class="reserv-place-info">
                <p>Reservation for:</p>
                <p><?=$place_reservation['title']?></p>
                <p><?=$place_reservation['address']?></p>
            </section>
            <section class="reserv-price">
                <p>Total price: <?=number_format($place_reservation['price'], 2);?> €</p>
            </section>
            <section class="row place-reserv-dates">
		    		<div>
		    			<p>From:</p>
		    			<p><?=$place_reservation['startDate']?></p>
		     		</div>
		    		<div>
		    		 	<p>Until:</p>
		    			<p><?=$place_reservation['endDate']?></p>
		     		</div>
            </section>
            <section class="column">
                <?php if(canCancelReservation($place_reservation['startDate'])) { ?>
                        <a class="button cancel-button" href="#" data-id="<?=$place_reservation['reservationID']?>"> 
                            Cancel
                        </a>
                <?php } ?>
            </section>
    </article> 
<?php } ?>