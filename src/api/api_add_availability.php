<?php
    include_once('../includes/session_include.php');
	include_once('../database/db_user.php');
	include_once('../includes/reservation_utils.php');

	if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
		echo json_encode(array('message' => 'user not logged in'));
		return;
	}

	$placeID = $_POST['placeID'];
	$avBegin = $_POST['avBegin'];
	$avEnd = $_POST['avEnd'];
	$price = $_POST['price'];

	if($placeID == null || $placeID == '' || $avBegin == null || $avBegin == '' || $avEnd == null || $avEnd == '' || $price == '' || $price == null){
		echo json_encode(array('message' => 'incomplete data'));
		return;
	}

	$place = getPlace($placeID);
	if($place['ownerID'] != $_SESSION['userID']){
		echo json_encode(array('message' => 'not owner', 'userID' => $_SESSION['userID']));
		return;
	}

	if(!is_numeric($price)){
		echo json_encode(array('message' => 'invalid price'));
		return;
	}

	if(!validateDate($avBegin) || !validateDate($avEnd) || $avBegin >= $avEnd || $avBegin < date('Y-m-d')){
		echo json_encode(array('message' => 'invalid date'));
		return;
	}

	if(!empty(getOverlapAvailability($placeID, $avBegin, $avEnd))){
		echo json_encode(array('message' => 'overlap availability'));
		return;
	}
	
	// TODO: verificação de startDate and endDate??
	$returnValue = newAvailability($placeID, $avBegin, $avEnd, $price);

	// TODO: verificar isto
	if($returnValue === true) {
		echo json_encode(array('message' => 'availability successfull'));
		return;
	}

	echo json_encode(array('message' => $returnValue));
?>