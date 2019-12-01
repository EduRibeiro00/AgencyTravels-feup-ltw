'use strict'

//// Sticky nav bar
var navbar = document.getElementById("navbar")
var sticky =  navbar.offsetTop

window.addEventListener('scroll', function(){
	if(window.pageYOffset >= sticky)
		navbar.classList.add("sticky")
	else
		navbar.classList.remove("sticky")
})


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

resultDropdown.addEventListener('click', function(event) {
	locInput.value = event.target.innerText
	resultDropdown.innerHTML = ""
});

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
		'nPeople': adults + children,
		'minPrice': data.get('minPrice') ? data.get('minPrice') : 0,
		'maxPrice': data.get('maxPrice') ? data.get('maxPrice') : 1000,
		'nRooms': data.get('nRooms') ? data.get('nRooms') : 0,
		'nBathrooms': data.get('nBathrooms') ? data.get('nBathrooms') : 0,
		'rating': data.get('rating') ? data.get('rating')[0] : 0
	};
	
	return parsedData
}

//////// Filters
let form = document.getElementById('search_form')
form.addEventListener("submit", function(event){
	event.preventDefault()
	// TODO: ver se há outra forma de fazer isto
	let formData = new URLSearchParams(new FormData(form))
	let parsedData = parseSearchData(formData)
	// console.log(parsedData)
	
	let request = new XMLHttpRequest()

	request.open("get", "../api/api_search.php?" + encodeForAjax(parsedData), true)
	request.send()

	request.addEventListener('load', function() {
		let answer = JSON.parse(this.responseText)
		console.log(answer)
	})

})