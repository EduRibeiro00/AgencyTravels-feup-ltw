<?php
	include_once('../includes/session_include.php');
	include_once('../database/db_places.php');
	include_once('../includes/input_validation.php');
	
	if(isset($_POST["val"])&&validateTextValue($_POST["val"])){	
		$locations = getLocations($_POST["val"]);

		$hints = array();

		foreach($locations as $location)
			array_push($hints, array('country' => $location["country"], 'city' => $location["city"]));

		echo json_encode(array('hints' => $hints));
	}

	// if ($_SERVER["REQUEST_METHOD"] == "GET") {
	// 	$places = getFilteredPlaces($_GET["nPeople"], $_GET["rating"], $_GET["nRooms"], $_GET["nBathrooms"]);
	// 	//$filtered = array();

	// 	//foreach($places as $place)
	// 	//	array_push($filtered, array('title' => $place["title"], 'rating' => $place["rating"], 'capacity' => $place["capacity"], 'numRooms' => $place["numRooms"], 'numBathrooms' => $place["numBathrooms"], 'gpsCoords' => $place["gpsCoords"], 'image' => $place["image"]));

	// 	//echo json_encode(array('filtered' => $filtered));
	// 	echo json_encode($places);
	// }
?>