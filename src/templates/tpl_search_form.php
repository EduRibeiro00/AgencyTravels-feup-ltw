<?php function draw_search_form() { ?>
	<div id="filters">
		<fieldset id="dates_field">
			<h4>Dates</h4>
			<!-- TODO: mudar para current date mais 1 depois -->
			<label for="checkin">Check In</label>
			<input type="date" id="checkin" name="checkin" min="2019-11-23">
			<label for="checkout">Check Out</label>
			<input type="date" id="checkout" name="checkout">
		</fieldset>
		<fieldset id="guests_field">
			<h4>Guests</h4>
			<label for="adults">Adults</label>
			<input type="number" id="adults" name="nAdults" min=1 value=1>
			<label for="children">Children</label>
			<input type="number" id="children" name="nChildren" min=0 value=0>
		</fieldset>
		<fieldset id="price_field">
			<h4>Price</h4>
			<!-- TODO: min < max -->
			<label for="MinPrice">Min Price</label>
			<input type="number" id="MinPrice" name="minPrice" value=0 min=0>
			<label for="MaxPrice">Max Price</label>
			<!-- TODO: por 1000 as 1000++ -->
			<input type="number" id="MaxPrice" name="maxPrice" value=1000 max=1000>
		</fieldset>
		<fieldset id="rating_field">
			<h4>Rating</h4>
			<input type = "radio" name = "rating" valor = "5stars" id = "5stars">
			<label for="5stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></label>
			<input type = "radio" name = "rating" valor = "4stars" id = "4stars">
			<label for="4stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>+</label>
			<input type = "radio" name = "rating" valor = "3stars" id = "3stars">
			<label for="3stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>+</label>
			<input type = "radio" name = "rating" valor = "2stars" id = "2stars">
			<label for="2stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>+</label>
			<input type = "radio" name = "rating" valor = "1stars" id = "1stars">
			<label for="1stars"><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>+</label>
		</fieldset>
		<fieldset id="facilities_field">
			<h4>Facilities</h4>
			<label for="rooms">Rooms</label>
			<input type="number" id="rooms" name="nRooms" min=0 value=0>
			<label for="bathrooms">Bathooms</label>
			<input type="number" id="bathrooms" name="nBathrooms" min=0 value=0>
		</fieldset>
		<input type="submit" value="Search">
	</div>
<?php } ?>
