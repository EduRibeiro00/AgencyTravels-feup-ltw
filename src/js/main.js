'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------------

//// Sticky nav bar
var navbar = document.getElementById("navbar")
var sticky = navbar.offsetTop

window.addEventListener('scroll', function() {
	if(window.pageYOffset >= sticky)
		navbar.classList.add("sticky")
	else
		navbar.classList.remove("sticky")
})

// -------------------

//// Footer
window.addEventListener('load', function() {
	let html = document.documentElement;
	let bodyHeight = html.scrollHeight - 30;

	if(bodyHeight < screen.height) {
		let footer = document.querySelector('body > footer');
		footer.style.position = "fixed";
		footer.style.bottom = "0";
		footer.style.left = "0";
		footer.style.right = "0";
	}
});


// -------------------

//// Search and Suggestions
// TODO: refactor disto [0]??
let locInput = document.getElementsByName("location")[0]
let resultDropdown = document.getElementById('search-hints')

locInput.addEventListener("keyup", function() {
	let request = new XMLHttpRequest()
	resultDropdown.innerHTML = ""
	if(locInput.value == "") return


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

function parsedLocationHint(locHint){
	if(locHint == null) return ""
	let res = locHint.split(" - ")
	if(res.length != 2 || res[0] == null || res[1] == null) return ""
	return "?location=" + res[0] + "+-+" + res[1]
	// let res = locHint.split(" - ")
	// if(res.length != 2 || res[0] == null || res[1] == null) return ""
	// return "?country=" + res[0] + "&city=" + res[1]
}

let searchSymbol = document.querySelector(".fa-search")
searchSymbol.addEventListener("click", function() {
	window.location = "list_places.php" + parsedLocationHint(locInput.value)
})

// -------------------

// TODO: n está belo
let crosses = document.getElementsByClassName('close-popup')
for(let x = 0; x < crosses.length; x++){
    crosses[x].addEventListener('click', function() {
		this.parentElement.parentElement.style.display = "none"
	});
}

