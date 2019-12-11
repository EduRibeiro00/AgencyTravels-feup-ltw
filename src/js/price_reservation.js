'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let priceForm = document.querySelector('#fr_card form');


priceForm.addEventListener('submit', function(event) {
    event.preventDefault();
	
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_price_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		let priceEl = document.getElementById('side_price_per_night')

		switch(message) {        
			case 'Invalid submission', -3:
				// TODO: por algo mais bonito
				priceEl.innerHTML = "Submission missing parameters"
				break;
			case -2:
				priceEl.innerHTML = "Wrong Range: there is a gap in Availabilities"
				break;
			case -1:
				priceEl.innerHTML = "Reservation Overlap"
				break;
            default:
				priceEl.innerHTML=message+'€'
				break;
        }
    
    });
    
	let placeID = document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="placeID"]').value
	let checkin = document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="check_in_date"]').value
	let checkout = document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="check_out_date"]').value

    request.send(encodeForAjax({placeID:placeID, checkin: checkin, checkout:checkout}));
});

// -----------