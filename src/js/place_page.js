'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

//Sticky sideBar_Fast reservation

window.onload = function () {
	let img_elem = document.getElementById('carousel_container')

	let someElement = document.getElementById('Pop_UP_Fast_Reservation')
	const value = img_elem.offsetHeight + navbar.offsetHeight - window.screenY
	window.addEventListener('scroll', function () {

		if (window.pageYOffset >= (value - 100)) {

			someElement.style.top = "4em"
		}
		else {

			someElement.style.top = "0"
		}

	})
}