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

function priceDay(date){
	if(date == null)
		return
	let req = new XMLHttpRequest()

	req.open("POST", "../api/api_place_info.php", true)
	req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
	
	req.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).price
		console.log(message)
		inlineCal.showPrice(message + "€")

	});
	let url = new URL(window.location.href)
	let placeID = url.searchParams.get("place_id")
	req.send(encodeForAjax({placeID: placeID, date: date.format('YYYY-MM-DD')}))
}

//Sticky sideBar_Fast reservation
window.onload = function () {
	let navbar = document.querySelector('#navbar');

	let someElement = document.getElementById('Pop_UP_Fast_Reservation')
	const value = navbar.offsetHeight - window.screenY
	window.addEventListener('scroll', function () {
		if (window.pageYOffset >= (value - 100)) {
			someElement.style.top = "4em"
		}
		else {
			someElement.style.top = "0"
		}

	})
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_info.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		// TODO: ver erros
		let message = JSON.parse(this.responseText).message
		inlineCal.setMinDate(message.startDate)
		inlineCal.setMaxDate(message.endDate)

		let disableDates = []
		if(message.invalidDates != null){
			message.invalidDates.forEach(iD => {
				disableDates.push([iD['startDate'], iD['endDate']])
			})

			inlineCal.setDisableDates(disableDates)
		}

	});
	let url = new URL(window.location.href)
	let placeID = url.searchParams.get("place_id")
	request.send(encodeForAjax({placeID: placeID}))
}