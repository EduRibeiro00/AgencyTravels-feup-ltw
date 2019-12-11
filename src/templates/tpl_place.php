<?php

include_once('../templates/tpl_common.php');
include_once('../templates/tpl_availability.php');
include_once('../templates/tpl_comment.php');


function draw_place_info_body($place, $houseComments, $houseOwnerInfo, $housePrice) { ?>
	<main id="place_page">
		<section class="column">
		<?php
			draw_my_place_icon_desc($place['title'], $place['numRooms'], $place['capacity'], $place['numBathrooms'], $place['description']);
			
			//Adress string parsing
			$houseAddress = $place['address'] . ", " . $place['city'] . ", " . $place['country'];
			draw_my_place_location($houseAddress, $place['gpsCoords']);
	  
		  	//House Rating is the avg rating of the house
			draw_all_comments($place['rating'], $houseComments);

			draw_availability_block();

		?>
		</section>
		<?php 
			draw_my_place_sidebar($housePrice, $place['rating'], $houseOwnerInfo, $place['placeID']); 
			draw_similar_offer_slide_show();  
		?>
	  </main>
<?php } 

function draw_my_place_sidebar($housePrice,$house_rating, $house_owner_info, $placeID) { ?>
    <aside>
		<!-- TODO: ver se article or div -->
		<article id="fr_card">
			<section id=Pop_UP_Fast_Reservation_Review_Short>
				<p>Price per night</p>
				<p id="side_price_per_night"><?=$housePrice?>€</p>
				<?php draw_star_rating($house_rating) ?>
			</section>

			<form id="Pop_UP_Fast_Reservation_Inputs">
				<?php if($placeID != null) { ?>
					<input type="hidden" name="placeID" value=<?=$placeID?>>
				<?php } ?>
				<input id="fr_checkin" type="text" name="check_in_date">
				<input id="fr_checkout" type="text" name="check_out_date">
				<input id="Book_Submit_Button" type="submit">
			</form>

			<section id="Owner_info">
				<a href="../pages/profile_page.php?userID=<?=$house_owner_info['userID']?>">
					<img id="Owner_Img" class="circular-img" src="../assets/images/users/small/<?=$house_owner_info['image']?>">
				</a>
				<span><?=$house_owner_info['name']?></span>
				<form>
					<button id="Button_Send_Message">Send Message</button>
				</form>
			</section>
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


//TODO:Implement Google MAPS
function draw_my_place_location($house_address_full, $house_gpsCoords) { ?>
    <article id="Google_Maps_Widget_Container">
        <header>Location</header>
        <section id="Google_Maps_Widget">
            <img id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">
        </section>
        <footer>
            <p>Address:<?= $house_address_full ?></p>
            <p>GPS_Coords:<?= $house_gpsCoords ?></p>
        </footer>
    </article>
<?php } ?>