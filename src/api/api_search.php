<?php
	include_once('../database/db_places.php');
	
	if(isset($_POST["val"])){	
		$locations = getLocations($_POST["val"]);

		$hints = array();

		foreach($locations as $location)
			array_push($hints, array('country' => $location["country"], 'city' => $location["city"]));

		echo json_encode(array('hints' => $hints));
	}

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$places = getFilteredPlaces($_GET["nPeople"], $_GET["rating"], $_GET["nRooms"], $_GET["nBathrooms"]);
		$hints = array();

		echo json_encode($places);
	}
?>