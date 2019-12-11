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
			draw_my_place_location($houseAddress, $place['gpsCoords']);
	  
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

function draw_my_place_sidebar($housePrice,$house_rating, $house_owner_info, $placeID, $numVotes) { ?>
    <aside>
		<!-- TODO: ver se article or div -->
		<article id="fr_card">
			<section id=Pop_UP_Fast_Reservation_Review_Short>
				<p><strong id="fr-price"><?=$housePrice?>€</strong> per night</p>
				<?php draw_star_rating($house_rating) ?>
				<a href='#reviews'>(<?=$numVotes?> Reviews)</a>
			</section>

			<form id="Pop_UP_Fast_Reservation_Inputs">
				<?php if($placeID != null) { ?>
					<input type="hidden" name="placeID" value=<?=$placeID?>>
				<?php } ?>
				<input id="fr_checkin" type="text" name="check_in_date" autocomplete="off">
				<input id="fr_checkout" type="text" name="check_out_date" autocomplete="off">
				<button class="button" id="Book_Submit_Button" type="submit">Reserve</button>
			</form>

			<section id="Owner_info">
				<a href="../pages/profile_page.php?userID=<?=$house_owner_info['userID']?>">
					<img id="Owner_Img" class="circular-img" src="../assets/images/users/small/<?=$house_owner_info['image']?>">
				</a>
				<span><?=$house_owner_info['name']?></span>
				<a class="button" href = "mailto: <?=$house_owner_info['email']?>">Send Mail</a>
			</section>
		</article>
    </aside>
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
    <article id="House_Info">
		<h3>Description</h3>
        <p id="House_Description"> <?= $house_description ?></p>
    </article>
<?php }


//TODO:Implement Google MAPS
function draw_my_place_location($house_address_full, $house_gpsCoords) { ?>
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
<?php } ?>