'use strict'

//// Sticky nav bar
var navbar = document.getElementById("navbar")
var sticky = navbar.offsetTop

window.addEventListener('scroll', function() {
	if(window.pageYOffset >= sticky)
		navbar.classList.add("sticky")
	else
		navbar.classList.remove("sticky")
})

//// Seach appearance
let searchForm = document.getElementById("search_form")
searchForm.addEventListener("focusin", function() {
	document.getElementById("filters").style.display = "grid"
	window.addEventListener('click', closeWindow)
})

searchForm.addEventListener("click", function() {
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


//// Login
// TODO: depois mudar para class e ver como fechar qd carregar fora. alem do close up procurar pai com esta classe
let popup = document.getElementById('pop-up')

let login = document.getElementById('loginlink')
login.addEventListener('click', function(){
	popup.style.display = "block";
})

window.addEventListener('click', function(event){
	if (event.target == popup) 
        popup.style.display = "none"
})

// TODO: n está belo
let crosses = document.getElementsByClassName('close-popup')
for(let x = 0; x < crosses.length; x++){
    crosses[x].addEventListener('click', function() {
		this.parentElement.parentElement.style.display = "none"
	});
}



function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

//// Search and Suggestions
// TODO: refactor disto [0]??
let locInput = document.getElementsByName("location")[0]
let resultDropdown = document.getElementById('search-hints')

locInput.addEventListener("keyup", function() {
	let request = new XMLHttpRequest()
	resultDropdown.innerHTML = ""
	if(locInput.value == "") {
		return
	}

	request.open("POST", "../api/api_search.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
	
	request.addEventListener('load', function() {
		let answer = JSON.parse(this.responseText)
		let hints = answer.hints;

		for(let idx in hints) {
			let newHint = document.createElement('p');
			newHint.innerHTML = hints[idx].country + " - " + hints[idx].city;
			resultDropdown.appendChild(newHint);
		}
	})
	
	request.send(encodeForAjax({val: locInput.value}))
})

resultDropdown.addEventListener('mouseup', function(event) {
	locInput.value = event.target.innerText
	resultDropdown.innerHTML = ""
})

//Sticky sideBar_Fast reservation

window.onload=function(){
	let img_elem=document.getElementById('carousel_container')
	
	let someElement = document.getElementById('Pop_UP_Fast_Reservation')
	const value= img_elem.offsetHeight+navbar.offsetHeight-window.screenY
	console.log(img_elem.clientHeight)
	console.log(navbar.clientHeight)
	console.log(value)

	window.addEventListener('scroll', function(){
	
		if(window.pageYOffset >= (value-100)){
			console.log(window.pageYOffset)
			someElement.style.top="4em"
		}
		else{
			console.log(window.pageYOffset)
			someElement.style.top="0"
		}
			
	})
}
