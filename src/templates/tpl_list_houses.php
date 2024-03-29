<?php 
include_once('../templates/tpl_cards.php');
include_once('../includes/reservation_utils.php');
include_once('../database/db_places.php');
include_once('../includes/google_maps.php');
include_once('../includes/input_validation.php');


function getPlaces(){
	$location = $_GET['location'];
	if($location != null && !validateLocationValue($location)) {
		return false;
	}

	$prov = explode(" - ", $location);
	if(count($prov)<2){
		$foundLoc=false;
	}else{
		$foundLoc = ($prov[0] != null && $prov[1] != null);
	}

	$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : null;
	$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : null;

	if(($checkin != null && !validateDateValue($checkin)) || ($checkout != null && !validateDateValue($checkout))) {
		return false;
	}

	$minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : 0;		// check
	$maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : 1000;	// check
	
	if(!validatePosIntValue($minPrice) || !validatePosIntValue($maxPrice)) {
		return false;
	}

	$foundDates = ($checkin != null && $checkout != null);

	$adults = isset($_GET['nAdults']) ? $_GET['nAdults'] : 1;			// check
	$children = isset($_GET['nChildren']) ? $_GET['nChildren'] : 0;	// check
	$rating = isset($_GET['rating']) ? $_GET['rating'][0] : 0;			// check

	if(!validatePosIntValue($adults) || !validatePosIntValue($children) || !validatePosIntValue($rating)) {
		return false;
	}

	$nRooms = isset($_GET['nRooms']) ? $_GET['nRooms'] : 0;				// check
	$nBathrooms = isset($_GET['nBathrooms']) ? $_GET['nBathrooms'] : 0;	// check

	if(!validatePosIntValue($nRooms) || !validatePosIntValue($nBathrooms)) {
		return false;
	}

	$nPeople = $adults + $children;
	if($foundLoc)
		$places = getFilteredPlacesLoc($nPeople, $rating, $nRooms, $nBathrooms, $prov[1], $prov[0]);
	else
		$places = getFilteredPlaces($nPeople, $rating, $nRooms, $nBathrooms, $location);
	
			foreach($places as $key => &$place){
		if(!$foundDates)
			$place['price'] = getAveragePrice($place['placeID']);
		else{
			$place['price'] = getPriceInDate($place['placeID'], $checkin, $checkout);
			if($place['price'] < 0) {
				unset($places[$key]);
				continue;
			}
		}
		if(!($place['price'] <= $maxPrice && $place['price'] >= $minPrice)) unset($places[$key]);
	}
	return $places;
}
				

function list_houses($places, $drawingOption, $userID, $location, $drawMap = false) { ?>
	<main id="list_places" class="row">
		<?php if(isset($location)){ ?>
			<div id="location_place_holder" data-location="<?=htmlspecialchars($location)?>"></div>
		<?php } ?>

		<section id="house_results">
			<?php 
			if($drawingOption == 'My_Houses')
				draw_availability_form();
			if(empty($places)) { ?>
				<em>No houses available</em>
			<?php } else if($places === false) { ?>
				<em>The inputs inserted are invalid. Please try again.</em>
			<?php } else {
				foreach ($places as $place){
					draw_horizontal_card($place, $drawingOption, $userID);
				}
			}
			?>
		</section>

		<?php if(!empty($places) && $drawMap) { ?>
			<section id="house_results_map">
				<?php initGoogleMaps(); ?>
			</section>

		 <?php } ?>
	</main>
<?php } 


function draw_availability_form() { ?>
	<div id="avail-popup" class="pop-up">
		<form class="animate column">
			
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

			<i class="close-popup fas fa-times"></i>
			<h3>Choose Availabilities</h3>

			<label for="price">Price</label>
			<input id="av_price" step="0.01" type="number" placeholder="Price per Night" name="price" required>
			<label for="av_begin">Availability</label>
			<div class="row">
				<input id="av_begin" type="text" autocomplete="off" placeholder="From..." name="av_begin" required>
				<input id="av_end" type="text" autocomplete="off" placeholder="To..." name="av_end" required>
			</div>
			<p id="av-error" class="error-message"></p>

			<button id="av-conf-button" class="button" type="submit">Add Availabilities</button>
		</form>
	</div>
<?php } ?>