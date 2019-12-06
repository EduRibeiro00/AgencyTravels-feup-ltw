'use strict'

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

let checkin = document.getElementById('checkin')
let checkout = document.getElementById('checkout')

///// Min Checkin
function setMinimumCheckin() {
	let today = new Date()
	let todayHTML = today.getFullYear()+'-'+('0'+(today.getMonth()+1)).slice(-2)+'-'+('0'+(today.getDate())).slice(-2)
	checkin.min = todayHTML
}

setMinimumCheckin()

//// Checkout > checkin
checkin.addEventListener('change', updateCheckout)

function updateCheckout() {
	let firstdate = checkin.value
	if(firstdate == null) firstdate = checkin.min

	let fdJS = new Date(firstdate)

	fdJS.setDate(fdJS.getUTCDate()+1)

	let nextDay = fdJS.getFullYear()+'-'+('0'+(fdJS.getMonth()+1)).slice(-2)+'-'+('0'+(fdJS.getDate())).slice(-2)
	
	checkout.min = nextDay
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


function parseSearchData(data){
	// for(let kd of data){
	// 	console.log(kd + " - " + data.get(data))
	// }
	let adults = data.get('nAdults') ? data.get('nAdults') : 1
	let children = data.get('nChildren') ? data.get('nChildren') : 0
	
	let parsedData = {
		'location': data.get('location'),
		'checkin': data.get('checkin'),
		'checkout': data.get('checkout'),
		'nPeople': parseInt(adults) + parseInt(children),
		'minPrice': data.get('minPrice') ? data.get('minPrice') : 0,
		'maxPrice': data.get('maxPrice') ? data.get('maxPrice') : 1000,
		'nRooms': data.get('nRooms') ? data.get('nRooms') : 0,
		'nBathrooms': data.get('nBathrooms') ? data.get('nBathrooms') : 0,
		'rating': data.get('rating') ? data.get('rating')[0] : 0
	};
	
	return parsedData
}

//////// Filters
/*let form = document.getElementById('search_form')
let houseSection = document.getElementById('house_results')
form.addEventListener("submit", function(event){
	event.preventDefault()
	houseSection.innerHTML = ""
	// TODO: ver se há outra forma de fazer isto
	let formData = new URLSearchParams(new FormData(form))
	let parsedData = parseSearchData(formData)
	// console.log(parsedData)
	
	let request = new XMLHttpRequest()

	request.open("get", "../api/api_search.php?" + encodeForAjax(parsedData), true)
	request.send()

	request.addEventListener('load', function() {
		let answer = JSON.parse(this.responseText)
		for(let house of answer)
			houseSection.appendChild(renderHouseCard(house))
	})

})*/



//////// House Cards
/*function renderHouseCard(place){
	let article = document.createElement("article")
	article.classList.add("row")
	article.classList.add("card")
	article.innerHTML = `
		<!-- TODO: mudar para carroussel -->
		<a href="#"><img class="hcard-img" src="${place['image']}"></a>
		<div class="column info">
			<h4>${place['title']}</h4>
			<!-- TODO: meter flexbox row, com divs ou spans -->
			<p><span class="card-guests">${place['capacity']} guests</span><span class="card-bedroom">${place['numRooms']} bedroom</span><span class="card-bathroom">${place['numBathrooms']} bathroom</span></p>
			<footer class="row">
			<!-- TODO: preco correto -->
				<p>45€/noite</p>
				<div class="card-rating">
					<?php draw_star_rating(${place['rating']});?>
					<!-- TODO: numero direito -->
					(229)
				</div>
			</footer>
		</div>
	`;
	return article
}*/