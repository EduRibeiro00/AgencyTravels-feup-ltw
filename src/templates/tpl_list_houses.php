<?php 
include_once('../templates/tpl_house_card.php');
include_once('../database/db_places.php');

function getPlaces(){
	$location = $_GET['location'];
	$prov = explode(" - ", $location);
	$foundLoc = ($prov[0] != null && $prov[1] != null);

	if (isset($_GET['search'])) {
		// TODO: parsed parameters

		$adults = $_GET['nAdults'] ? $_GET['nAdults'] : 1;			// check
		$children = $_GET['nChildren'] ? $_GET['nChildren'] : 0;	// check
		$rating = $_GET['rating'] ? $_GET['rating'][0] : 0;			// check


		$minPrice = $_GET['minPrice'] ? $_GET['minPrice'] : 0;
		$maxPrice = $_GET['maxPrice'] ? $_GET['maxPrice'] : 1000;
		$nRooms = $_GET['nRooms'] ? $_GET['nRooms'] : 0;			// check
		$nBathrooms = $_GET['nBathrooms'] ? $_GET['nBathrooms'] : 0;// check

		$checkin = $_GET['checkin'];
		$checkout = $_GET['checkout'];


		
		// if(res.length != 2 || res[0] == null || res[1] == null) return ""
		// return "?location=" + res[0] + "+-+" + res[1]
	


		$nPeople = $adults + $children;
		if($foundLoc)
			$places = getFilteredPlacesFoundLoc($nPeople, $rating, $nRooms, $nBathrooms, $prov[1], $prov[0]);
		else
			$places = getFilteredPlaces($nPeople, $rating, $nRooms, $nBathrooms, $location);
		if($checkin == null && $checkout == null){
			foreach($places as &$place)
				$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
		}
		else{
			echo "TODO: else if checkin != null";
		}
		return $places;
	}
	if($foundLoc)
		$places = getFilteredPlacesFoundLoc(1, 0, 1, 0, $prov[1], $prov[0]);
	else
		$places  = getFilteredPlaces(1, 0, 1, 0, $location);
	
	foreach($places as &$place)
		$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
	return $places;
}
				
// TODO ver parametros depois
function list_houses_result($places) { ?>
	<main class="row">
		<section id="house_results">
			<?php 
			if(empty($places))
				echo "<p> TODO: msg de qd n há casa que correspondem à pesquisa </p>";
			else
				foreach ($places as $place)
					draw_horizontal_card($place);
			?>
		</section>
		<section id="map">
			<img src="https://via.placeholder.com/1024?text=Maps+Placeholder">
		</section>
	</main>
<?php } ?>