//// Seach appearance
let searchForm = document.getElementById("search_form")
searchForm.addEventListener("focusin", openFilters)
searchForm.addEventListener("focusout", closeFilters)


function openFilters(){
	document.getElementById("filters").style.display = "grid"
}

function closeFilters(event){
	if (searchForm.contains(event.relatedTarget))
		return;
	document.getElementById("filters").style.display = "none"
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