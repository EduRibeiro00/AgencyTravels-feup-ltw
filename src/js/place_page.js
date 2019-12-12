'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------


// -------------

let inlineCal = new Lightpick({
	field			: document.getElementById('av_checkin'),
	format			: 'YYYY-MM-DD',
	lang			: 'en-US',
	dropdowns		: {
			years: {
				min: 2019,
				max: 2139,
			},
			months: true
	},
	footer			: true,
	numberOfMonths	: 3,
	numberOfColumns	: 3,
	inline			: true,
	priceTooltip	: true,
	onSelect		: priceDay
});

let checkin = document.getElementById('fr_checkin')
let checkout = document.getElementById('fr_checkout')

let reservationCal = new Lightpick({
	field			: checkin,
    secondField		: checkout,
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
	numberOfMonths	: 1,
	singleDate		: false,
	tooltipNights	: true,
	fixed			: true,
	disabledDatesInRange: false,
	onSelectEnd		: getPriceAsync

});


function getPriceAsync() {
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_price_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		let priceEl = document.getElementById('fr-price')

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
    
	let placeID = document.querySelector('#fr_card input[name="placeID"]').value
	let checkin = document.getElementById('fr_checkin').value
	let checkout = document.getElementById('fr_checkout').value

    request.send(encodeForAjax({placeID:placeID, checkin: checkin, checkout:checkout}));
}

function priceDay(date){
	if(date == null)
		return
	let req = new XMLHttpRequest()

	req.open("POST", "../api/api_place_info.php", true)
	req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
	
	req.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).price
		inlineCal.showPrice(message + "€")

	});
	let url = new URL(window.location.href)
	let placeID = url.searchParams.get("place_id")
	req.send(encodeForAjax({placeID: placeID, date: date.format('YYYY-MM-DD')}))
}

//Sticky sideBar_Fast reservation
window.onload = function () {
	let navbar = document.getElementById('navbar')
	let fastRes = document.getElementById('fr_card')
	let checkinFR = document.getElementById('fr_checkin')
	
	fastRes.style.top = navbar.offsetHeight + "px"

	let fr = document.getElementById('fr_card')
	window.addEventListener('scroll', function(){
		if(window.pageYOffset == fr.offsetTop - navbar.offsetHeight)
			reservationCal.setPositionProp('fixed', checkinFR.offsetHeight + checkinFR.offsetTop + navbar.offsetHeight);
		else
			reservationCal.setPositionProp('', fr.offsetTop + checkinFR.offsetHeight + checkinFR.offsetTop);

	})
	

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_info.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		// TODO: ver erros
		let message = JSON.parse(this.responseText).message
		inlineCal.setMinDate(message.startDate)
		inlineCal.setMaxDate(message.endDate)

		reservationCal.setMinDate(message.startDate)
		reservationCal.setMaxDate(message.endDate)

		let disableDates = []
		if(message.invalidDates != null){
			message.invalidDates.forEach(iD => {
				disableDates.push([iD['startDate'], iD['endDate']])
			})

			inlineCal.setDisableDates(disableDates)
			reservationCal.setDisableDates(disableDates)
		}

	});
	let url = new URL(window.location.href)
	let placeID = url.searchParams.get("place_id")
	request.send(encodeForAjax({placeID: placeID}))
}

let frForm = document.querySelector('#fr_card form')
let frPopup = document.getElementById('fr-popup')
let frMessage = document.getElementById('fr-message')

frForm.addEventListener('submit', function(event) {
	event.preventDefault();
	frPopup.style.display = "block";
	

	frMessage.textContent = "";
	frMessage.style.display = "none";


	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		console.log(message)
		// TODO: botoes
		switch(message) {
            case 'user not logged in':
				frMessage.textContent = 'ERROR: User is not logged in';
                frMessage.style.display = "block";
				break;
			case 'incomplete data':
				frMessage.textContent = 'ERROR: Data Received was incomplete';
                frMessage.style.display = "block";
				break;
			case 'reservation overlap':
				frMessage.textContent = 'ERROR: Your Reservation Overlaps one existent Reservation';
                frMessage.style.display = "block";
				break;
			case 'inexsitent availability':
				frMessage.textContent = 'ERROR: Days in Range without Availability';
                frMessage.style.display = "block";
				break;
			case 'overlap own reservation':
				frMessage.textContent = 'You already have one Reservation in the date range. Are you sure you want to continue?';
                frMessage.style.display = "block";
				break;
			case 'own place':
				frMessage.textContent = 'This is your own Place. Are you sure you want to continue?';
                frMessage.style.display = "block";
				break;
            default:
				break;
		}
	});

	let hiddenInput = frForm.children[0]
	if(hiddenInput == null){
		frPopup.style.display = "none";
		return;
	}

    let frCheckin = document.getElementById('fr_checkin').value;
	let frCheckout = document.getElementById('fr_checkout').value;

    request.send(encodeForAjax({placeID: hiddenInput.value, checkin: frCheckin, checkout: frCheckout}));
});

window.addEventListener('click', function(event){
	if (event.target == frPopup)
		frPopup.style.display = "none";
});
