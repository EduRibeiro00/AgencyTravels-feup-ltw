<?php

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_comment.php');
include_once('../templates/tpl_similar_offer.php');
include_once('../includes/reservation_utils.php');
include_once('../includes/google_maps.php');
include_once('../templates/tpl_cards.php');



function draw_place_info_body($place, $houseComments, $houseOwnerInfo, $housePrice) { 
	draw_confirmation_form();
	?>
	<main id="place_page">
		<section class="column">
		<h2><?=$place['title']?></h2>
		<?php
			draw_place_details($place['numRooms'], $place['capacity'], $place['numBathrooms']);
			draw_place_description($place['description']);
			
			//Adress string parsing
			$houseAddress = $place['address'] . ", " . $place['city'] . ", " . $place['country'];
			draw_place_location($houseAddress, $place['gpsCoords']);
	  
		  	//House Rating is the avg rating of the house
			draw_all_comments($place['rating'], $houseComments);

            if(isset($_SESSION['userID']) && $_SESSION['userID'] != "") {
                $reservationID = canUserReviewPlace($_SESSION['userID'], $place['placeID']);
                if($reservationID !== false) {
                    draw_add_review($reservationID, $place['placeID']);
                }
            }

			draw_availability();
		?>
		</section>
		<?php 
			draw_my_place_sidebar($housePrice, $place['rating'], $houseOwnerInfo, $place['placeID'], count($houseComments)); 
			draw_similar_offer_slide_show();  
		?>
	  </main>
<?php } 

function draw_place_details($house_numRooms, $house_capacity, $house_numBathrooms) { ?>
	<ul class="row">
		<li class="button-appearance">
			<i class="fas fa-bed"></i> <?=$house_numRooms?>
		</li>
		<li class="button-appearance">
			<i class="fas fa-user"></i> <?=$house_capacity?>
		</li>
		<li class="button-appearance">
			<i class="fas fa-toilet"></i> <?=$house_numBathrooms?>
		</li>
	</ul>
<?php }

function draw_place_description($house_description) { ?>
    <article id="house_description">
		<h3>Description</h3>
        <p> <?= $house_description ?></p>
    </article>
<?php }

function draw_availability(){ ?>
    <article id="availabilities">
		<h3>Availabilities</h3>
		<input id="av_checkin" type="text">
    </article>
<?php }

function draw_my_place_sidebar($housePrice,$house_rating, $houseOwner, $placeID, $numVotes) { ?>
    <aside>
		<!-- TODO: ver se article or div -->
		<article id="fr_card">
			<section>
				<p><strong id="fr-price"><?=$housePrice?>€</strong> per night</p>
				<?php draw_star_rating($house_rating) ?>
				<a href='#reviews'>(<?=$numVotes?> Reviews)</a>
				<p><?=number_format($house_rating, 1);?>/5.0</p>
			</section>

			<form>
				<input id="fr_checkin" type="text" autocomplete="off" placeholder="Check In..." required>
				<input id="fr_checkout" type="text" autocomplete="off" placeholder="Check Out..." required>

				<button class="button" type="submit">Reserve</button>
			</form>
			<?php draw_user_card($houseOwner, 'email');?>
		</article>
    </aside>
<?php } 


function draw_my_place_icon_desc($house_name, $house_numRooms, $house_capacity, $house_numBathrooms, $house_description) { ?>
    <article id="House_Info">
        <header><?= $house_name ?></header>
        <ul id="Pictographic_Info">
            <li>
                <i class="fas fa-bed"></i>
                Number of Rooms <?= $house_numRooms ?>
            </li>
            <li>
                <i class="fas fa-user"></i>
                Capacity <?= $house_capacity ?>
            </li>
            <li>
                <i class="fas fa-toilet"></i>
                Bathrooms <?= $house_numBathrooms ?>
            </li>
        </ul>
        <p id="House_Description"> <?= $house_description ?></p>
    </article>
<?php }


function draw_place_location($house_address_full, $house_gpsCoords) { ?>
    <article id="Google_Maps_Widget_Container">
        <header>Location</header>
        <section id="Google_Maps_Widget">
            <?php initGoogleMaps(false,true); ?>
        </section>
        <footer>
            <p>Address:<?= $house_address_full ?></p>
            <p id="PlaceGPSCoords">GPS_Coords:<?= $house_gpsCoords ?></p>
        </footer>
    </article>
<?php }


function draw_add_review($reservationID, $placeID) { ?>
    <section id="add-review-placeholder">
        <article class="review" data-reviewID="">
			<header>
				<?php draw_user_card($comment, 'rating'); ?>
			</header>
            <p></p>
            <footer>
                <p></p>
            </footer>

            <section class="comment-replies">
            </section>

            <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] != "") { ?>
                    <section class="add-reply-section">
                        <p>Add a reply:</p>
                        <form class="reply-form row">
                            <label for="reply-desc">Comment:
                                <textarea rows="5" cols="50" name="reply-desc"></textarea>
                            </label>
                            <input class="button" type="submit" value="Submit">
                        </form>
                    </section>
            <?php } ?>

        </article>
    </section>

    <section id="add-review-section">
        <h4>Thank you for staying in this place! We hope you enjoyed your stay.</h4>
        <p>Leave a review...</p>
        <form id="review-form" class="row">
            <input type="hidden" name="reservationID" value=<?=$reservationID?>>
            <input type="hidden" name="placeID" value=<?=$placeID?>>
            <label for="review-stars">Stars:
                <input type="number" name="review-stars" min="1" max="5" step="1" required>
            </label>
            <label for="review-desc">Comment:
                <textarea rows="5" cols="50" name="review-desc"></textarea>
            </label>
            <input class="button" type="submit" value="Submit">
        </form>
    </section>
<?php } ?>