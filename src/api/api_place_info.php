<?php
// TODO: mudar nome e se calhar por tbm o api_price_reservation.php
include_once('../includes/session_include.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');
include_once('../includes/input_validation.php');

	if(!isset($_POST['placeID']) || $_POST['placeID'] == ''||!validateIntValue($_POST['placeID'])) {
		echo json_encode(array('message' => 'Error in placeID'));
		return;
	}

	$placeID = $_POST['placeID'];

	if(isset($_POST['date'])) {
		$price = getPrice($placeID, $_POST['date'])['price'];
		echo json_encode(array('price' => $price));
		return;
	}
	else{

	$availabilities = getFromDayForwardAvailabilities($placeID, date("Y-m-d"));
	$reservations = getFromDayForwardReservations($placeID, date("Y-m-d"));

	usort($availabilities, 'compareDates');
	usort($reservations, 'compareDates');


	// Merge availabilities
	$x = -1;
	foreach ($availabilities as $key => $availability) {
		if($availability['startDate'] == $resultAv[$x]['endDate'])
			$resultAv[$x]['endDate'] = $availability['endDate'];
		else
			$resultAv[++$x] = ['startDate'=>$availability['startDate'],'endDate'=>$availability['endDate']];
	}

	// Merge reservations
	$x = -1;
	foreach ($reservations as $key => $reserva) {
		if($reserva['startDate'] == $resultRes[$x]['endDate'])
			$resultRes[$x]['endDate'] = $reserva['endDate'];
		else
			$resultRes[++$x] = ['startDate'=>$reserva['startDate'],'endDate'=>$reserva['endDate']];
	}

	// Update begin and end dates
	foreach ($resultRes as $key => &$reserva) {
		$reserva['startDate'] = date('Y-m-d',strtotime("{$reserva['startDate']} +1 day"));
		$reserva['endDate'] = date('Y-m-d',strtotime("{$reserva['endDate']} -1 day"));
		if(strtotime($reserva['startDate']) > strtotime($reserva['endDate']))
			unset($resultRes[$key]);
	}

	for($i = 0; $i < count($resultAv) - 1; $i++){
		$endDate = date('Y-m-d',strtotime("{$resultAv[$i + 1]['startDate']} -1 day"));
		$startDate = date('Y-m-d',strtotime("{$resultAv[$i]['endDate']} +1 day"));
		if(strtotime($startDate) <= strtotime($endDate))
			$resultRes[] = array('startDate' => $startDate, 'endDate' => $endDate);
	}

	// Cenas importantes:
	// - initial date da primeira availability: 
	// 		-> $resultAv[0]['startDate']
	// - final date da ultima availability:
	// 		-> $resultAv[count($resultAv) - 1]['endDate']
	// - ocupados/invalidos: 
	// 		-> $resultRes
	$finalRes = array('startDate' => (date("Y-m-d") >  $resultAv[0]['startDate']) ? date("Y-m-d"): $resultAv[0]['startDate'], 'endDate' => $resultAv[count($resultAv) - 1]['endDate'], 'invalidDates' => $resultRes);
	echo json_encode(array('message' => $finalRes));
	}

?>