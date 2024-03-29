<?php
	include_once('../includes/session_include.php');
	include_once('../includes/input_validation.php');
	include_once('../templates/tpl_common.php');
	include_once('../templates/tpl_list_houses.php');
	include_once('../database/db_user.php');

	if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
		$user_info = getUserInformation($_SESSION['userID']);
		$jsFiles = ['../js/main.js', '../js/filter.js','../js/googleMapsHouseList.js'];
	}
	else {
		$user_info = NULL;
		$jsFiles = ['../js/main.js', '../js/login.js', '../js/filter.js','../js/googleMapsHouseList.js'];
	}

	$location=$_GET['location'];
	if(!isset($location)||is_numeric($location)||!validateLocationValue($location)){
		die(header('Location: ../pages/index.php'));
	}
	

	$places = getPlaces();
	draw_head($jsFiles);
	draw_navbar($user_info, true);
	list_houses($places, 'Search', null,$location, true);
	draw_footer();
?>