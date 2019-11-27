<?php
	include_once('../database/db_places.php');
	
	if(isset($_POST["val"])){	
		$locations = getLocations($_POST["val"]);

		$hints = array();

		foreach($locations as $location)
			array_push($hints, array('country' => $location["country"], 'city' => $location["city"]));

		echo json_encode(array('hints' => $hints));
	}
?>