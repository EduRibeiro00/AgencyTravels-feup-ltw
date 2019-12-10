<?php 
include_once('../templates/tpl_house_card.php');
include_once('../templates/tpl_reservation_utils.php');
include_once('../database/db_places.php');

function getPlaces(){
	$location = $_GET['location'];
	$prov = explode(" - ", $location);
	$foundLoc = ($prov[0] != null && $prov[1] != null);

	$checkin = $_GET['checkin'];
	$checkout = $_GET['checkout'];

	$minPrice = $_GET['minPrice'] ? $_GET['minPrice'] : 0;		// check
	$maxPrice = $_GET['maxPrice'] ? $_GET['maxPrice'] : 1000;	// check
	
	// TODO: mudar para ou e se tiver apenas 1 -> somar um dia / subtrair um dia à outra
	$foundDates = ($checkin != null && $checkout != null);
	if($foundDates){
		$ph  = explode("/", $checkin);
		$checkin = $ph[2] . "-" . $ph[1] . "-" . $ph[0];

		$ph  = explode("/", $checkout);
		$checkout = $ph[2] . "-" . $ph[1] . "-" . $ph[0];
	}

	if (isset($_GET['search'])) {
		// TODO: parsed parameters

		$adults = $_GET['nAdults'] ? $_GET['nAdults'] : 1;			// check
		$children = $_GET['nChildren'] ? $_GET['nChildren'] : 0;	// check
		$rating = $_GET['rating'] ? $_GET['rating'][0] : 0;			// check


		$nRooms = $_GET['nRooms'] ? $_GET['nRooms'] : 0;			// check
		$nBathrooms = $_GET['nBathrooms'] ? $_GET['nBathrooms'] : 0;// check
		$nPeople = $adults + $children;
		if($foundLoc)
		 	$places = getFilteredPlacesLoc($nPeople, $rating, $nRooms, $nBathrooms, $prov[1], $prov[0]);
		else
		 	$places = getFilteredPlaces($nPeople, $rating, $nRooms, $nBathrooms, $location);
		
			 foreach($places as $key => &$place){
			if(!$foundDates)
				$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
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
	if($foundLoc)
		$places = getFilteredPlacesLoc(1, 0, 1, 0, $prov[1], $prov[0]);
	else
		$places  = getFilteredPlaces(1, 0, 1, 0, $location);
	
	foreach($places as $key => &$place){
		$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
		if($maxPrice != 1000)
			if(!($place['price'] <= $maxPrice && $place['price'] >= $minPrice)) unset($places[$key]);
		else
			if(!($place['price'] >= $minPrice)) unset($places[$key]);
	}
	return $places;
}
				
// TODO ver parametros depois
function list_houses_result($places, $drawingOption, $drawMap = false) { ?>
	<main id="list_places" class="row">
		<section id="house_results">
			<?php 
			if(empty($places)) { ?>
				<em>No houses available</em>
			<?php } else
				foreach ($places as $place){
					draw_horizontal_card($place, $drawingOption);
				}
			?>
		</section>

		<!-- TODO: implementar maps com google maps API em JS -->
		<?php if(!empty($places) && $drawMap) { ?>
			<section id="map">
				<img src="https://via.placeholder.com/1024?text=Maps+Placeholder">
			</section>
		<?php } ?>

	</main>
<?php } ?>
