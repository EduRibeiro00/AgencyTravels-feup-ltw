<?php 
include_once('../templates/tpl_house_card.php');
include_once('../database/db_places.php');

function getPlaces(){
	if (isset($_GET['search'])) {
		// TODO: parsed parameters

		$adults = $_GET['nAdults'] ? $_GET['nAdults'] : 1;
		$children = $_GET['nChildren'] ? $_GET['nChildren'] : 0;
		$rating = $_GET['rating'] ? $_GET['rating'][0] : 0;


		$minPrice = $_GET['minPrice'] ? $_GET['minPrice'] : 0;
		$maxPrice = $_GET['maxPrice'] ? $_GET['maxPrice'] : 1000;
		$nRooms = $_GET['nRooms'] ? $_GET['nRooms'] : 0;
		$nBathrooms = $_GET['nBathrooms'] ? $_GET['nBathrooms'] : 0;

		$checkin = $_GET['checkin'];
		$checkout = $_GET['checkout'];
		$location = $_GET['location'];


		$nPeople = $adults + $children;
		$places  = getFilteredPlaces($nPeople, $rating, $nRooms, $nBathrooms);
		if($checkin == null && $checkout == null){
			foreach($places as &$place)
				$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
		}
		else{
			echo "TODO: else if checkin != null";
		}
		return $places;
	}
	$places = getFilteredPlaces(1, 0, 1, 0);
	foreach($places as &$place)
		$place['price'] = getAveragePrice($place['placeID'])['avg_price'];
	return $places;
}
				
// TODO ver parametros depois
function list_houses_result($places) { ?>
	<main class="row">
		<section id="house_results">
			<?php 
			foreach ($places as $place){
				draw_horizontal_card($place);
			}
			?>
		</section>
		<section id="map">
			<img src="https://via.placeholder.com/1024?text=Maps+Placeholder">
		</section>
	</main>
<?php } ?>