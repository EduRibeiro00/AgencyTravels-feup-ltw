'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

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
}