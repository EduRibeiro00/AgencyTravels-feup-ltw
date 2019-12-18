<?php
	include_once('../includes/session_include.php');
	include_once('../database/db_places.php');
	include_once('../includes/input_validation.php');
	if ($_SESSION['csrf'] !== $_POST['csrf']) {
		$message=$_POST;
		echo json_encode(array('message' => $message));
		return;
	}
	
	if(isset($_POST["val"])){	
		$locations = getLocations($_POST["val"]);

		$hints = array();

		foreach($locations as $location)
			array_push($hints, array('country' => $location["country"], 'city' => $location["city"]));

		echo json_encode(array('hints' => $hints));
	}
	else {
		$hints='no';
		echo json_encode(array('hints' => $hints));
	}
?>