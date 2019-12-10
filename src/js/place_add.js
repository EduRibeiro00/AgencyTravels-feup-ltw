'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let profileForm = document.querySelector('#place_edit_form form');
console.log(profileForm)

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_add.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function () {
		console.log(this.responseText)
		let message = JSON.parse(this.responseText).message;

		
		switch(message) {
            case 'true':
				//errorMessage.style.display = "none";
                break;
			
            default:
				history.back();
				break;
		}

    });
    
    let ownerID = document.querySelector('#place_edit_form input[name="userID"]').value;
	let title = document.querySelector('#place_edit_form input[name="title"]').value;
	let desc = document.querySelector('#place_edit_form textarea[name="description"]').value;
	let address = document.querySelector('#place_edit_form input[name="address"]').value;
	let city = document.querySelector('#place_edit_form input[name="city"]').value;
	let country = document.querySelector('#place_edit_form input[name="country"]').value;
	let numRooms = document.querySelector('#place_edit_form input[name="numRooms"]').value;
	let numBathrooms = document.querySelector('#place_edit_form input[name="numBathrooms"]').value;
	let capacity = document.querySelector('#place_edit_form input[name="capacity"]').value;
	
	
	request.send(encodeForAjax({title: title, desc: desc, address: address, city: city, country: country, numRooms: numRooms, numBathrooms: numBathrooms, capacity: capacity,ownerID:ownerID}));
});

// -----------