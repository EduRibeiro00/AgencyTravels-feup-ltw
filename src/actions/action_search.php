<?php

	include_once('../database/db_connection.php');

	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * 
						FROM Location
						WHERE country LIKE ? OR city LIKE ?');
	

	if(isset($_GET["val"])){
		$val = "%" . $_GET["val"] ."%";
		$stmt->execute(array($val, $val));
		$locations = $stmt->fetchAll();

		foreach($locations as $location)
			echo "<p>" . $location["country"] . " - " . $location["city"] . "</p>";
	}
?>