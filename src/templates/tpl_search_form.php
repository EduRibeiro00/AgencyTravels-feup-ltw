<?php function draw_search_form() { ?>
	<form action="../actions/action_search.php" method="get">
		<fieldset>
			<h4>Dates</h4>
			<!-- TODO: mudar para current date mais 1 depois -->
			<input type="date" name="checkin" min="2019-11-23">
			<input type="date" name="checkout">
		</fieldset>
		<fieldset>
			<h4>Guests</h4>
			<input type="number" name="nAdults" min=0>
			<input type="number" name="nChildren" min=0>
		</fieldset>
		<fieldset>
			<h4>Price</h4>
			<!-- TODO: min < max -->
			<input type="number" name="minPrice" min=0>
			<input type="number" name="maxPrice" max=1000>
		</fieldset>
		<fieldset>
			<h4>Rating</h4>
			<input type = "radio" name = "rating" valor = "5stars">
			<input type = "radio" name = "rating" valor = "4stars">
			<input type = "radio" name = "rating" valor = "3stars">
			<input type = "radio" name = "rating" valor = "2stars">
			<input type = "radio" name = "rating" valor = "1stars">
		</fieldset>
		<fieldset>
			<h4>Rooms</h4>
			<input type="number" name="nRooms" min=0>
		</fieldset>
		<fieldset>
			<h4>Bathrooms</h4>
			<input type="number" name="nBathrooms" min=0>
		</fieldset>
	</form>
<?php } ?>
