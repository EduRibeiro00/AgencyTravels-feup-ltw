<?php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');
include_once('../includes/input_validation.php');

	if ($_SESSION['csrf'] !== $_POST['csrf']) {
		$message='token error';
		echo json_encode(array('message' => $message));
		return;
	}
	
	$placeID = $_POST['placeID'];

	if(!validatePosIntValue($placeID)) {
		$message = 'Error in placeID';
		echo json_encode(array('message' => $message));
		return;
	}

	if(isset($_POST['date']) && validateDateValue($_POST['date'])) {
		$price = getPrice($placeID, $_POST['date'])['price'];
		echo json_encode(array('price' => $price));
		return;
	}
	else{

	$availabilities = getFromDayForwardAvailabilities($placeID, date("Y-m-d"));
	if($availabilities == null){
		$today = date('Y-m-d');
		$nextday = strtotime("{$today} +1 day");
		$finalRes = array('startDate' => date('Y-m-d', $nextday), 'endDate' => date("Y-m-d"), 'invalidDates' => []);
		echo json_encode(array('message' => $finalRes));
		return;
	}

	usort($availabilities, 'compareDates');


	// Merge availabilities
	$x = 0;
	$resultAv[0] = ['startDate'=> (date("Y-m-d") >  $availabilities[0]['startDate']) ? date("Y-m-d"): $availabilities[0]['startDate'],'endDate'=>$availabilities[0]['endDate']];
	for($i = 1; $i < count($availabilities); $i++) {
		$availability = $availabilities[$i];

		if($availability['startDate'] == $resultAv[$x]['endDate'])
			$resultAv[$x]['endDate'] = $availability['endDate'];
		else
			$resultAv[++$x] = ['startDate'=> $availability['startDate'],'endDate'=>$availability['endDate']];
	}

	$reservations = getFromDayForwardReservations($placeID, date("Y-m-d"));

	if($reservations == null){
		$finalRes = array('startDate' => $resultAv[0]['startDate'], 'endDate' => $resultAv[count($resultAv) - 1]['endDate'], 'invalidDates' => []);
		echo json_encode(array('message' => $finalRes));
		return;
	}
	
	for($i = 0; $i < count($resultAv) - 1; $i++){
		$endDate = date('Y-m-d',strtotime("{$resultAv[$i + 1]['startDate']} -1 day"));
		$startDate = date('Y-m-d',strtotime("{$resultAv[$i]['endDate']} +1 day"));
		if(strtotime($startDate) <= strtotime($endDate))
			$reservations[] = array('startDate' => $startDate, 'endDate' => $endDate);
	}

	usort($reservations, 'compareDates');

	// Merge reservations
	$x = 0;
	$resultRes[0] = ['startDate'=>$reservations[0]['startDate'],'endDate'=>$reservations[0]['endDate']];
	for($i = 1; $i < count($reservations); $i++) {
		$reserva = $reservations[$i];

		if($reserva['startDate'] == $resultRes[$x]['endDate'])
			$resultRes[$x]['endDate'] = $reserva['endDate'];
		else
			$resultRes[++$x] = ['startDate'=>$reserva['startDate'],'endDate'=>$reserva['endDate']];
	}

	
	// Update begin and end dates
	$newRes;
	for ($i = 0; $i < count($resultRes); $i++) {
		if(strtotime($resultRes[$i]['startDate']) != strtotime(date('Y-m-d')))
			$resultRes[$i]['startDate'] = date('Y-m-d',strtotime("{$resultRes[$i]['startDate']} +1 day"));
		$resultRes[$i]['endDate'] = date('Y-m-d',strtotime("{$resultRes[$i]['endDate']} -1 day"));
		if(!(strtotime($resultRes[$i]['startDate']) > strtotime($resultRes[$i]['endDate']))){
			$newRes[] = $resultRes[$i];
		}
	}

	// Cenas importantes:
	// - initial date da primeira availability: 
	// 		-> $resultAv[0]['startDate']
	// - final date da ultima availability:
	// 		-> $resultAv[count($resultAv) - 1]['endDate']
	// - ocupados/invalidos: 
	// 		-> $resultRes
	$finalRes = array('startDate' => $resultAv[0]['startDate'], 'endDate' => $resultAv[count($resultAv) - 1]['endDate'], 'invalidDates' => $newRes);
	echo json_encode(array('message' => $finalRes));
	}

?>