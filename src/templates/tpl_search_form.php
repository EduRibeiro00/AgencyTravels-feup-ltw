<?php 
// TODO: ver isto de inclusao circular
include_once('../templates/tpl_common.php');

function draw_search_form() { ?>
	<div id="filters">
		<fieldset id="dates_field">
			<h4>Dates</h4>
			<label for="checkin">Check In</label>
			<input type="text" id="checkin" name="checkin">
			<label for="checkout">Check Out</label>
			<input type="text" id="checkout" name="checkout">
		</fieldset>
		<fieldset id="guests_field">
			<h4>Guests</h4>
			<label for="adults">Adults
				<input type="number" id="adults" name="nAdults" min=1 value=1 step=1>
			</label>
			<label for="children">Children
				<input type="number" id="children" name="nChildren" min=0 value=0 step=1>
			</label>
		</fieldset>
		<fieldset id="price_field">
			<h4>Price</h4>
			<label for="MinPrice">Min Price
				<input type="number" id="MinPrice" name="minPrice" value=0 min=0 max=999 step=1>
			</label>
			<label for="MaxPrice">Max Price
			<!-- TODO: por 1000 as 1000++ -->
				<input type="number" id="MaxPrice" name="maxPrice" value=1000 max=1000 step=1>
			</label>
		</fieldset>
		<fieldset id="rating_field">
			<h4>Rating</h4>
			<label for="5stars">
				<input type = "radio" name = "rating" value = "5stars" id = "5stars"><?php draw_star_rating(5); ?> 5
			</label>
			<label for="4stars">
				<input type = "radio" name = "rating" value = "4stars" id = "4stars"><?php draw_star_rating(4); ?> 4+
			</label>
			<label for="3stars">
				<input type = "radio" name = "rating" value = "3stars" id = "3stars"><?php draw_star_rating(3); ?> 3+
			</label>
			<label for="2stars">
				<input type = "radio" name = "rating" value = "2stars" id = "2stars"><?php draw_star_rating(2); ?> 2+
			</label>
			<label for="1stars">
				<input type = "radio" name = "rating" value = "1stars" id = "1stars"><?php draw_star_rating(1); ?> 1+
			</label>
		</fieldset>
		<fieldset id="facilities_field">
			<h4>Facilities</h4>
			<label for="rooms">Rooms
				<input type="number" id="rooms" name="nRooms" min=0 value=0 step=1>
			</label>
			<label for="bathrooms">Bathooms
				<input type="number" id="bathrooms" name="nBathrooms" min=0 value=0 step=1>
			</label>
		</fieldset>
		<input class="button" type="submit" name="search" value="search">
	</div>
<?php } ?>
