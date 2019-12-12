<?php

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_comment.php');
include_once('../templates/tpl_similar_offer.php');
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
		<li class="button">
			<i class="fas fa-bed"></i> Rooms <?=$house_numRooms?>
		</li>
		<li class="button">
			<i class="fas fa-user"></i> Capacity <?=$house_capacity?>
		</li>
		<li class="button">
			<i class="fas fa-toilet"></i> Bathrooms <?=$house_numBathrooms?>
		</li>
	</ul>
<?php }

function draw_place_description($house_description) { ?>
    <article id="house_description">
		<h3>Description</h3>
        <p> <?= $house_description ?></p>
    </article>
<?php }

//TODO:Implement Google MAPS
function draw_place_location($house_address_full, $house_gpsCoords) { ?>
    <article>
        <h3>Location</h3>
        <img style="height: auto; width:80%" id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">
        <footer>
            <p>Address: <?= $house_address_full ?></p>
            <p>GPS Coords: <?= $house_gpsCoords ?></p>
        </footer>
    </article>
<?php } 


function draw_availability(){ ?>
    <article id="availabilities">
		<h3>Availabilties</h3>
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
				<p><?=$house_rating?>/5.0</p>
			</section>

			<form>
				<?php if($placeID != null) { ?>
					<input type="hidden" name="placeID" value=<?=$placeID?>>
				<?php } ?>
				<input id="fr_checkin" type="text" name="check_in_date" autocomplete="off" placeholder="Check In..." required>
				<input id="fr_checkout" type="text" name="check_out_date" autocomplete="off" placeholder="Check Out..." required>

				<button class="button" type="submit">Reserve</button>
			</form>
			<?php draw_user_card($houseOwner, 'email');?>
		</article>
    </aside>
<?php } 

function draw_confirmation_form() { ?>
	<div id="fr-popup" class="pop-up">
		<form id="fr-confirmation" class="animate">
			<i class="close-popup fas fa-times"></i>
			<p id="fr-message"></p>
			<button id="confirm-button" class="button" type="submit">Confirm</button>
			<button id="cancel-button" class="button" type="reset">Cancel</button>

		</form>
	</div>
<?php } ?>