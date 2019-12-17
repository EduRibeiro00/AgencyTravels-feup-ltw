<?php
include_once('../database/db_places.php');
include_once('../database/db_user.php');

function compareDates($element1, $element2) { 
    $datetime1 = strtotime($element1['startDate']); 
    $datetime2 = strtotime($element2['startDate']); 
    return $datetime1 - $datetime2; 
}  

function timeToDay($time){
	return round($time / (60 * 60 * 24));
}

function getPriceInDate($placeID, $checkin, $checkout){
	if(!empty(getOverlapReservations($placeID, $checkin, $checkout)))
		return -1;
	
	$availabilities = getCompatibleAvailability($placeID, $checkin, $checkout);
	
	usort($availabilities, 'compareDates'); 


	$acc = 0;
	$nDays = timeToDay(strtotime($checkout) - strtotime($checkin));
	for($i = 0; $i < count($availabilities); $i++){
		$av = $availabilities[$i];

		$endTime = strtotime($av['endDate']);
		$checkinTime = strtotime($checkin);
		$checkoutTime = strtotime($checkout);		

		if($checkinTime < strtotime($av['startDate']))
			return -2;
		
		if($endTime >= $checkoutTime) {
			$diff = $checkoutTime - $checkinTime;
			return round((timeToDay($diff) * $av['pricePerNight'] + $acc) / $nDays, 2);
		}

		$diff = $endTime - $checkinTime;
		$checkin = $av['endDate'];
		$acc += timeToDay($diff) * $av['pricePerNight'];
	}

	return -3;
}

function getAvailabilites($placeID){
	$availabilities = getFromDayForwardAvailabilities($placeID, date("Y-m-d"));

	usort($availabilities, 'compareDates'); 
	$x = -1;
	for($i = 0; $i < count($availabilities); $i++) {
		$availability = $availabilities[$i];

		if($availability['startDate'] == $resultAv[$x]['endDate'])
			$resultAv[$x]['endDate'] = $availability['endDate'];
		else
			$resultAv[++$x] = ['startDate'=>$availability['startDate'],'endDate'=>$availability['endDate']];
	}

	for($i = 0; $i < count($resultAv); $i++) {
		$availability = $resultAv[$i];
		$availability['startDate'] = date('Y-m-d',strtotime("{$availability['startDate']} +1 day"));
		$availability['endDate'] = date('Y-m-d',strtotime("{$availability['endDate']} -1 day"));
		if(strtotime($availability['startDate']) > strtotime($availability['endDate']))
			unset($resultAv[$key]);
	}

	return $resultAv;
}

// user can cancel reservation up to 3 days before
function canCancelReservation($reservationStartDate) {
	$currentDate = date('Y-m-d');
	$dateDifference = date_diff(date_create($currentDate), date_create($reservationStartDate));

	return ($reservationStartDate > $currentDate && $dateDifference->format('%a') >= 3);
} 


// can review place after reservation has ended, and if it there is no review yet
function canReviewPlace($reservationEndDate, $reviewID) {
	$currentDate = date('Y-m-d');
	return $currentDate > $reservationEndDate && $reviewID === false;
}

// user can review a place if at least one of the reservations has ended and has no review yet.
// returns reservation ID to which review is going to be attached to
function canUserReviewPlace($userID, $placeID) {
	$reservs = getUserReservationsForPlace($userID, $placeID);
	if($reservs === false) return false;

	foreach($reservs as $reserve) {
		if(canReviewPlace($reserve['endDate'], getReviewForReservation($reserve['reservationID']))) {
			return $reserve['reservationID'];
		}
	}
	return false;
}

?>