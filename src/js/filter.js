'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let checkin = document.getElementById('checkin')
let checkout = document.getElementById('checkout')

new Lightpick({
	field			: checkin,
	secondField		: checkout,
	lang			: 'en-US',
	format			: 'YYYY-MM-DD',
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
	tooltipNights	: true,
	fixed			: true
});

let cal = document.getElementsByClassName("lightpick")[0]
cal.addEventListener('click', function(ev) {
	ev.stopPropagation();
})


//// Seach appearance
let searchForm = document.getElementById("search_form")
searchForm.addEventListener("focusin", function() {
	document.getElementById("filters").style.display = "grid"
	window.addEventListener('click', closeWindow)
})

searchForm.addEventListener("click", function(event) {
	event.stopPropagation()
})

function closeWindow() {
	document.getElementById("filters").style.display = "none"
	resultDropdown.innerHTML = ""
	window.removeEventListener("click", closeWindow)
}

//// MaxPrice > Min Price
let minPrice = document.getElementById('MinPrice')
let maxPrice = document.getElementById('MaxPrice')

minPrice.addEventListener('change', updateMaxPrice);

function updateMaxPrice() {
	let minimun = minPrice.value
	if(minimun == null) minimun = minPrice.min
	
	maxPrice.min = parseInt(minimun) + 1
}

///// Radios

let radios = document.getElementsByName('rating')
let check;
for(let x = 0; x < radios.length; x++){
    radios[x].addEventListener('click', function() {
        if(check != this){
             check = this;
        }else{
            this.checked = false;
            check = null;
    	}
	});
}

///// Reset
let resetFilters = document.getElementById('reset-button')
resetFilters.addEventListener('click',  function(ev){
	ev.preventDefault()
	checkin.value = ''
	checkout.value = ''
	minPrice.value = 0
	maxPrice.value = 1000
	document.getElementById('adults').value = 1
	document.getElementById('children').value = 0
	for(let x = 0; x < radios.length; x++)
		radios[x].checked = ''
	document.getElementById('rooms').value = 0
	document.getElementById('bathrooms').value = 0
})
