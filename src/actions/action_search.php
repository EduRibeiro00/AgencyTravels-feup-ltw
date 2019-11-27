<?php
	include_once('../database/db_places.php');

	if(isset($_GET["val"])){	
		$locations = getLocations($_GET["val"]);

		foreach($locations as $location)
			echo "<p>" . $location["country"] . " - " . $location["city"] . "</p>";
	}
?>