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
			<label for="adults">Adults
				<input type="number" id="adults" name="nAdults" min=1 value=1>
			</label>
			<label for="children">Children
				<input type="number" id="children" name="nChildren" min=0 value=0>
			</label>
		</fieldset>
		<fieldset id="price_field">
			<h4>Price</h4>
			<!-- TODO: min < max -->
			<label for="MinPrice">Min Price
				<input type="number" id="MinPrice" name="minPrice" value=0 min=0>
			</label>
			<label for="MaxPrice">Max Price
			<!-- TODO: por 1000 as 1000++ -->
				<input type="number" id="MaxPrice" name="maxPrice" value=1000 max=1000>
			</label>
		</fieldset>
		<fieldset id="rating_field">
			<h4>Rating</h4>
			<label for="5stars">
				<input type = "radio" name = "rating" valor = "5stars" id = "5stars">
			<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> 5</label>
			<label for="4stars">
				<input type = "radio" name = "rating" valor = "4stars" id = "4stars">
			<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i> 4+</label>
			<label for="3stars">
				<input type = "radio" name = "rating" valor = "3stars" id = "3stars">
			<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 3+</label>
			<label for="2stars">
				<input type = "radio" name = "rating" valor = "2stars" id = "2stars">
			<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 2+</label>
			<label for="1stars">
				<input type = "radio" name = "rating" valor = "1stars" id = "1stars">
			<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 1+</label>
		</fieldset>
		<fieldset id="facilities_field">
			<h4>Facilities</h4>
			<label for="rooms">Rooms
				<input type="number" id="rooms" name="nRooms" min=0 value=0>
			</label>
			<label for="bathrooms">Bathooms
				<input type="number" id="bathrooms" name="nBathrooms" min=0 value=0>
			</label>
		</fieldset>
		<input class="button" type="submit" value="Search">
	</div>
<?php } ?>
