'use strict'


let av_begin = document.getElementById('av_begin')
let av_end = document.getElementById('av_end')

// -------------

let availabilityCal = new Lightpick({
	field			: av_begin,
    secondField		: av_end,
	format			: 'YYYY-MM-DD',
	lang			: 'en-US',
	dropdowns		: {
			years: {
				min: 2019,
				max: 2139,
			},
			months: true
	},
	minDate			: new Date(),
	minDays			: 2,
	autoclose		: false,
	footer			: true,
	numberOfMonths	: 2,
	singleDate		: false,
	fixed			: true,
	hoveringTooltip	: false,
	disabledDatesInRange: false,

});

// -------------

let availPlaceID; 
let priceInput = document.getElementById('av_price')

// -------------


document.getElementsByClassName('lightpick')[0].style.zIndex = 501
let avaButtons = document.querySelectorAll('a.add-avail');
let availPopup = document.getElementById('avail-popup')
for(let i = 0; i < avaButtons.length; i++) {
    let avaButton = avaButtons[i];

    avaButton.addEventListener('click', function(event) {
			availabilityCal.reset()
			priceInput.value = ""
			let pError = document.getElementById('av-error')
			pError.style.display = "none";
  		pError.textContent = "";
			event.preventDefault();
			availPopup.style.display = 'block'
			availPlaceID = avaButton.getAttribute('data-id');
			updateAvailabilityDisable(availPlaceID)
    });
}

let availForm = availPopup.children[0]

availForm.addEventListener('submit', function(event) {
	event.preventDefault();

	let request = new XMLHttpRequest();
	let pError = document.getElementById('av-error')
	pError.style.display = "none";
  pError.textContent = "";

	request.open("POST", "../api/api_add_availability.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText)

		switch(message.message) {
			case 'user not logged in':
				availPopup.style.display = 'none'
				showDialog("ERROR: You are Not Logged In")
				break;
			case 'incomplete data':
			  pError.style.display = "block";
				pError.textContent = "Data incomplete"
				break;
			case 'not owner':
				availPopup.style.display = 'none'
				showDialog("ERROR: That was not your house")
				break;
			case 'invalid price':
			  pError.style.display = "block";
				pError.textContent = "ERROR: Price must be a number"
				break;
			case 'invalid date':
				pError.style.display = "block";
				pError.textContent = "ERROR: Invalid date Range"
				break;
			case 'invalid inputs':
				pError.style.display = "block";
				pError.textContent = "ERROR: Error in the inputs"
				break;
				
			case 'overlap availability':
				pError.style.display = "block";
				pError.textContent = "ERROR: Overlapping Availabilities"
				break;
			
			case 'availability successfull':
	      pError.style.display = "none";				
				showDialog("Availability Added With Success")
				availPopup.style.display = 'none'
				break;
			
			case 'token error':
				break;

			default:
			  pError.style.display = "block";
				pError.textContent = "ERROR: " + message.message
				break;
		}
	});

	let csrf = availForm.querySelector('input[name="csrf"]').value;

	request.send(encodeForAjax({csrf: csrf, placeID: availPlaceID, avBegin: av_begin.value, avEnd: av_end.value, price: priceInput.value}));
})



function updateAvailabilityDisable(availPlaceID){
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_update_availability.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message

		switch(message) {
			case 'user not logged in':
				availPopup.style.display = 'none'
				showDialog("ERROR: You are Not Logged In")
				break;
			case 'incomplete data':
				showDialog("ERROR: Place ID not defined")
				break;
			case 'not owner':
				availPopup.style.display = 'none'
				showDialog("ERROR: That was not your house")
				break;
			default:
				let disableDates = []
				if(message != null){
					for(let i = 0; i < message.length; i++) {
						let iD = message[i];
						disableDates.push([iD['startDate'], iD['endDate']]);
					}

					availabilityCal.setDisableDates(disableDates)
				}
				break;
		}

	});


	request.send(encodeForAjax({placeID: availPlaceID}))
}

// ----------------

window.addEventListener('click', function(event){
	if (event.target == availPopup)
		availPopup.style.display = "none";
});
