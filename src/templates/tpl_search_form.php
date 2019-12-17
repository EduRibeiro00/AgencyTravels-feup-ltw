<?php 
include_once('../templates/tpl_common.php');

function draw_search_form() { 

	$checkin = isset($_GET['checkin']) && validateDateValue($_GET['checkin']) ? $_GET['checkin'] : null;
	$checkout = isset($_GET['checkout']) && validateDateValue($_GET['checkout']) ? $_GET['checkout'] : null;
	$minPrice = isset($_GET['minPrice']) && validatePosIntValue($_GET['minPrice']) ? $_GET['minPrice'] : 0;		// check
	$maxPrice = isset($_GET['maxPrice']) && validatePosIntValue($_GET['maxPrice']) ? $_GET['maxPrice'] : 1000;	// check
	$adults = isset($_GET['nAdults']) && validatePosIntValue($_GET['nAdults']) ? $_GET['nAdults'] : 1;				// check
	$children = isset($_GET['nChildren']) && validatePosIntValue($_GET['nChildren']) ? $_GET['nChildren'] : 0;		// check
	$rating = isset($_GET['rating']) && validatePosIntValue($_GET['rating'][0]) ? $_GET['rating'][0] : 0;			// check
	$nRooms = isset($_GET['nRooms']) && validatePosIntValue($_GET['nRooms']) ? $_GET['nRooms'] : 0;				// check
	$nBathrooms = isset($_GET['nBathrooms']) && validatePosIntValue($_GET['nBathrooms'])? $_GET['nBathrooms'] : 0;	// check

	?>
	<div id="filters">
		<fieldset id="dates_field">
			<h4>Dates</h4>
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
			<label for="checkin">Check In</label>
			<input type="text" pattern="[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])" autocomplete="off" id="checkin" name="checkin" value=<?=$checkin?>>
			<label for="checkout">Check Out</label>
			<input type="text" autocomplete="off" pattern="[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])" id="checkout" name="checkout" value=<?=$checkout?>>
		</fieldset>
		<fieldset id="guests_field">
			<h4>Guests</h4>
			<label for="adults">Adults
				<input type="number" id="adults" name="nAdults" min=1 step=1 value=<?=$adults?>>
			</label>
			<label for="children">Children
				<input type="number" id="children" name="nChildren" min=0 step=1 value=<?=$children?>>
			</label>
		</fieldset>
		<fieldset id="price_field">
			<h4>Price</h4>
			<label for="MinPrice">Min Price
				<input type="number" id="MinPrice" name="minPrice" min=0 max=999 step=1 value=<?=$minPrice?>>
			</label>
			<label for="MaxPrice">Max Price
				<input type="number" id="MaxPrice" name="maxPrice" max=1000 step=1 value=<?=$maxPrice?>>
			</label>
		</fieldset>
		<fieldset id="rating_field">
			<h4>Rating</h4>
			<label for="5stars">
				<input type = "radio" name = "rating" value = "5stars" id = "5stars" <?php if($rating == 5) echo 'checked'?>><?php draw_star_rating(5); ?> 5
			</label>
			<label for="4stars">
				<input type = "radio" name = "rating" value = "4stars" id = "4stars" <?php if($rating == 4) echo 'checked'?>><?php draw_star_rating(4); ?> 4+
			</label>
			<label for="3stars">
				<input type = "radio" name = "rating" value = "3stars" id = "3stars" <?php if($rating == 3) echo 'checked'?>><?php draw_star_rating(3); ?> 3+
			</label>
			<label for="2stars">
				<input type = "radio" name = "rating" value = "2stars" id = "2stars" <?php if($rating == 2) echo 'checked'?>><?php draw_star_rating(2); ?> 2+
			</label>
			<label for="1stars">
				<input type = "radio" name = "rating" value = "1stars" id = "1stars" <?php if($rating == 1) echo 'checked'?>><?php draw_star_rating(1); ?> 1+
			</label>
		</fieldset>
		<fieldset id="facilities_field">
			<h4>Facilities</h4>
			<label for="rooms">Rooms
				<input type="number" id="rooms" name="nRooms" min=0 step=1 value=<?=$nRooms?>>
			</label>
			<label for="bathrooms">Bathooms
				<input type="number" id="bathrooms" name="nBathrooms" min=0 step=1 value=<?=$nBathrooms?>>
			</label>
		</fieldset>
		<div id="filter_buttons" class="row">
		<button id="submit-button" class="button">Search</button>
		<button type="reset" class="button">Reset</button>
		</div>
	</div>
<?php } ?>
