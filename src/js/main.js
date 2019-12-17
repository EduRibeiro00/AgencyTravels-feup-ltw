'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------------

let navbar = document.getElementById("navbar")
let topDialog = document.getElementById("top-dialog")
topDialog.style.top = navbar.offsetHeight + "px"
let links = document.querySelector('nav ul')
let ham = document.getElementById("hamburger")
ham.addEventListener('click', function() {
	if(ham.checked == true){
		links.classList.add('responsive')
		links.style.top = navbar.offsetHeight + "px"
	}
	else
		links.classList.remove('responsive')
})

// -------------------

function showDialog(message) {
	topDialog.style.display = "flex"
	let messageDialog = document.querySelector("#top-dialog p")
	messageDialog.textContent = message

	setTimeout(function () {
		messageDialog.textContent = ""
		topDialog.style.display = "none"
	}, 2000)
}

// -------------------

//// Footer
window.addEventListener('load', function () {
	let body = document.body;
	let bodyHeight = body.scrollHeight;

	if (bodyHeight < screen.height) {
		let footer = document.querySelector('body > footer');
		footer.style.position = "fixed";
		footer.style.bottom = "0";
		footer.style.left = "0";
		footer.style.right = "0";
	}
});


window.onresize = function(event) {
	let body = document.body;
	let bodyHeight = body.offsetHeight;

	if (bodyHeight < screen.height) {
		let footer = document.querySelector('body > footer');
		footer.style.position = "fixed";
		footer.style.bottom = "0";
		footer.style.left = "0";
		footer.style.right = "0";
	}
};


// -------------------

//// Search and Suggestions
let locInput = document.getElementsByName("location")[0]
let resultDropdown = document.getElementById('search-hints')

locInput.addEventListener("keyup", function () {
	let request = new XMLHttpRequest()
	resultDropdown.innerHTML = ""
	if (locInput.value == "") return

	request.open("POST", "../api/api_search.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function () {
		let answer = JSON.parse(this.responseText)
		let hints = answer.hints;
		
		if(answer=='token error'){
			return;
		}
		if(hints == "no")
			return;

		for(let idx in hints) {
			let newHint = document.createElement('p');
			newHint.innerHTML = hints[idx].country + " - " + hints[idx].city;
			resultDropdown.appendChild(newHint);
		}
	})
	let dataToken=document.querySelector('input[name="csrf"]').value;
	request.send(encodeForAjax({val:locInput.value, csrf: dataToken}));
})

resultDropdown.addEventListener('mouseup', function (event) {
	locInput.value = event.target.innerText
	resultDropdown.innerHTML = ""
})

function parsedLocationHint(locHint) {
	if (locHint == null) return ""
	let res = locHint.split(" - ")
	if (res.length != 2 || res[0] == null || res[1] == null) return ""
	return "?location=" + res[0] + "+-+" + res[1]
}

let searchSymbol = document.querySelector(".fa-search")
searchSymbol.addEventListener("click", function () {
	window.location = "list_places.php" + parsedLocationHint(locInput.value)
})

// -------------------

// TODO: n está belo
let crosses = document.getElementsByClassName('close-popup')
for (let x = 0; x < crosses.length; x++) {
	crosses[x].addEventListener('click', function () {
		if (this.parentElement.parentElement.className == "pop-up")
			this.parentElement.parentElement.style.display = "none"
		else
			this.parentElement.style.display = "none"
	});
}


// ------------ Slide shows
let slideshow = document.getElementById('slideshow')
if(slideshow != null){
	document.querySelector('#slideshow img').addEventListener('load', function(){
		new Splide( '#slideshow', {
			type   : 'loop',
			cover: true,
			height: '40em',
			width: '70%',
			autoplay: true,
			interval: 3000,
			height: '50%',
			breakpoints: {
				960: {
					width: '90%',
				},
				1240: {
					width: '80%',
				},			
			},
		} ).mount();
	})
}

let hcards = document.getElementsByClassName('hcard_slideshow');
if(hcards.length > 0)
	for (let i = 0; i < hcards.length; i++) {
		hcards[i].querySelector('img').addEventListener('load', function(){
			new Splide(hcards[i], {
				type	: 'loop',
				width	: '16em',
			} ).mount();
		})
	}

let userPlaces = document.querySelectorAll('#profile-page .minplaces_slideshow');
if(userPlaces.length > 0)
	for (let i = 0; i < userPlaces.length; i++) {
		userPlaces[i].querySelector('img').addEventListener('load', function(){
			new Splide(userPlaces[i], {
				// width	: '100%',
				perPage	: 2,
				perMove : 2,
				focus: 'center',
				pagination: false,
				breakpoints: {
					1240: {
						perMove: 1,
						perPage: 1,
					},
					
				},
			} ).mount();
		})
	}

let minplaces = document.querySelectorAll('#main-page .minplaces_slideshow');
if(minplaces.length > 0)
	for (let i = 0; i < minplaces.length; i++) {
		minplaces[i].querySelector('img').addEventListener('load', function(){
			new Splide(minplaces[i], {
				perPage	: 3,
				perMove : 3,
				focus: 'center',
				pagination: false,
				breakpoints: {
					960: {
						perMove: 1,
						perPage: 1,
						// height: '50%'
					},
					1240: {
						perPage: 2,
						perMove : 2,
					},
					
				},
			} ).mount();
		})
	}