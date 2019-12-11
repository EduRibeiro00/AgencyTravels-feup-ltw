<?php

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_comment.php');
include_once('../templates/tpl_similar_offer.php');


function draw_place_info_body($place, $houseComments, $houseOwnerInfo, $housePrice) { ?>
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
    <article id="Google_Maps_Widget_Container">
        <h3>Location</h3>
        <img id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">
        <footer>
            <p>Address:<?= $house_address_full ?></p>
            <p>GPS_Coords:<?= $house_gpsCoords ?></p>
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
				<input id="fr_checkin" type="text" name="check_in_date" autocomplete="off" placeholder="Check In...">
				<input id="fr_checkout" type="text" name="check_out_date" autocomplete="off" placeholder="Check Out...">
				<button class="button" type="submit">Reserve</button>
			</form>

			<section id="owner_card">
				<a id="owner_img" href="../pages/profile_page.php?userID=<?=$houseOwner['userID']?>">
					<img class="circular-img" src="../assets/images/users/small/<?=$houseOwner['image']?>">
				</a>
				<a id="owner_username" href="../pages/profile_page.php?userID=<?=$houseOwner['userID']?>">
					<?=$houseOwner['name']?>
				</a>
				<a id="owner_contact" href="mailto: <?=$houseOwner['email']?>">Speak with the Owner</a>
			</section>
		</article>
    </aside>
<?php } 