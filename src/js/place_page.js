'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

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

let placeID = new URL(window.location.href).searchParams.get("place_id")

function getPriceAsync() {
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_price_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		let priceEl = document.getElementById('fr-price')

		switch(message) {        
			case 'Invalid submission':
			case -3:
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

function updateDisableDates(){
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

			for(let i = 0; i < message.invalidDates.length; i++) {
				let iD = message.invalidDates[i];
				disableDates.push([iD['startDate'], iD['endDate']]);
			}

			inlineCal.setDisableDates(disableDates)
			reservationCal.setDisableDates(disableDates)
		}

	});
	let url = new URL(window.location.href)
	let placeID = url.searchParams.get("place_id")
	request.send(encodeForAjax({placeID: placeID}))
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
	
	updateDisableDates()	
}

let frForm = document.querySelector('#fr_card form')
let confirmForm = document.getElementById('cf-confirmation')
let frPopup = document.getElementById('cf-popup')
let cancelBt = document.getElementById('cf-cancel-button')
let confirmBt = document.getElementById('cf-confirm-button')

let rowBt = document.querySelector('#cf-confirmation .row')


cancelBt.addEventListener('click', function(){
	frPopup.style.display = "none"
})

confirmBt.addEventListener('click', function(){
	frPopup.style.display = "none"
})

let frCheckin, frCheckout;

frForm.addEventListener('submit', function(event) {
	event.preventDefault();
	frPopup.style.display = "block";
	let frMessage = document.getElementById("cf-message")
	if(frMessage != null) frMessage.outerHTML = ""


    frCheckin = document.getElementById('fr_checkin').value
	frCheckout = document.getElementById('fr_checkout').value

	let date1 = new Date(frCheckin)
	let date2 = new Date(frCheckout)
	let diffTime = Math.abs(date2 - date1)
	let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let response = JSON.parse(this.responseText);

		switch(response.message) {
			case 'user not logged in':
				rowBt.parentNode.insertBefore(errorMessage('User is not logged in'), rowBt)
				confirmBt.style.display = "none";
				break;
			case 'incomplete data':
				rowBt.parentNode.insertBefore(errorMessage('Data Received was incomplete'), rowBt)
				confirmBt.style.display = "none";
				break;
			case 'reservation overlap':
				rowBt.parentNode.insertBefore(errorMessage('Your Reservation Overlaps one existent Reservation'), rowBt)
				confirmBt.style.display = "none";
				break;
			case 'inexsitent availability':
				rowBt.parentNode.insertBefore(errorMessage('Days in Range without Availability'), rowBt)
				confirmBt.style.display = "none";
				break;
			case 'overlap own reservation':
				rowBt.parentNode.insertBefore(confirmMessage('You already have one Reservation in the date range', response.price, diffDays), rowBt)
				confirmBt.style.display = "inline-block";
				break;
			case 'own place':
				rowBt.parentNode.insertBefore(confirmMessage('This is your own Place', response.price, diffDays), rowBt)
				confirmBt.style.display = "inline-block";
				break;
			case 'valid reservation':
				rowBt.parentNode.insertBefore(confirmMessage('', response.price, diffDays), rowBt)
				confirmBt.style.display = "inline-block"
				break;
			case 'invalidPlaceID':
				rowBt.parentNode.insertBefore(confirmMessage('', response.price, diffDays), rowBt)
				confirmBt.style.display = "Error in PlaceID";
				break;
			case 'invalid CheckInDates':
				rowBt.parentNode.insertBefore(confirmMessage('', response.price, diffDays), rowBt)
				confirmBt.style.display = "Error in checkindate";
				break;
			case 'invalid CheckOutDate':
				rowBt.parentNode.insertBefore(confirmMessage('', response.price, diffDays), rowBt)
				confirmBt.style.display = "Error in checkoutdate";
				break;

			default:
				
				break;
		}
	});

    request.send(encodeForAjax({placeID: placeID, checkin: frCheckin, checkout: frCheckout}))
});

confirmForm.addEventListener('submit', function(event) {
	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_add_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message

		switch(message) {
			case 'user not logged in':
				showDialog("ERROR: User is not logged in")
				break;
			case 'incomplete data':
				showDialog("ERROR: Incomplete Data Submission")
				break;
			case 'invalid dates':
				showDialog("ERROR: Invalid Dates")
				break;

			case 'invalid inputs':
				showDialog("Some of the inputs were invalid. Please try again.")
				break;

			case 'reservation successful':				
				showDialog("Reservation Successful")
				reservationCal.reset()
				updateDisableDates()
				break;
			default:
				showDialog("ERROR: " + message)
				break;
		}
	});

    request.send(encodeForAjax({placeID: placeID, checkin: frCheckin, checkout: frCheckout}));
})

window.addEventListener('click', function(event){
	if (event.target == frPopup)
		frPopup.style.display = "none";
});

function errorMessage(message){
	let article = document.createElement("article");
	article.innerHTML = `<p>ERROR: ${message}</p>`;
	article.setAttribute('id', 'cf-message');
	return article;
}

function confirmMessage(firstLine, price, nights){
	let article = document.createElement("article");
	let finalPrice = Math.round(price * nights * 100) / 100;
	article.innerHTML = `<p>${price}€/night <i class="fas fa-times"></i> ${nights} nights = <strong>${finalPrice}€</strong></p>`
	if(firstLine !== '')
		article.innerHTML += `<p>${firstLine}</p>`
		
	article.innerHTML += `<p>Are you sure you want to continue?</p>`;
	article.setAttribute('id', 'cf-message');
	return article;
}