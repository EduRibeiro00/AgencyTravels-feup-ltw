<?php
include_once('../database/db_places.php');

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
	
	$availabilties = getCompatibleAvailability($placeID, $checkin, $checkout);
	
	usort($availabilties, 'compareDates'); 


	$acc = 0;
	$nDays = timeToDay(strtotime($checkout) - strtotime($checkin));
	foreach($availabilties as $av){
		$endTime = strtotime($av['endDate']);
		$checkinTime = strtotime($checkin);
		$checkoutTime = strtotime($checkout);		

		if($checkinTime < strtotime($av['startDate']))
			return -1;
		
		if($endTime >= $checkoutTime) {
			$diff = $checkoutTime - $checkinTime;
			return round((timeToDay($diff) * $av['pricePerNight'] + $acc) / $nDays, 2);
		}

		$diff = $endTime - $checkinTime;
		$checkin = $av['endDate'];
		$acc += timeToDay($diff) * $av['pricePerNight'];
	}

	return -1;
}

// user can cancel reservation up to 3 days before
function canCancelReservation($reservationStartDate) {
	$currentDate = date('Y-m-d');
	$dateDifference = date_diff(date_create($currentDate), date_create($reservationStartDate));

	return ($reservationStartDate > $currentDate && $dateDifference->format('%a') >= 3);
} 


// user can review place after reservation has ended
function canReviewPlace($reservationEndDate, $reviewID) {
	$currentDate = date('Y-m-d');
	return $currentDate > $reservationEndDate && $reviewID === false;
}

?>