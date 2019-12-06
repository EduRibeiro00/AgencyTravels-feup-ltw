

let profileForm = document.querySelector('#place_edit_form form');

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_edit.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function () {
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

	let placeID = document.querySelector('#place_edit_form input[name="placeID"]').value;
	let title = document.querySelector('#place_edit_form input[name="title"]').value;
	let desc = document.querySelector('#place_edit_form textarea[name="description"]').value;
	let address = document.querySelector('#place_edit_form input[name="address"]').value;
	let city = document.querySelector('#place_edit_form input[name="city"]').value;
	let country = document.querySelector('#place_edit_form input[name="country"]').value;
	let numRooms = document.querySelector('#place_edit_form input[name="numRooms"]').value;
	let numBathrooms = document.querySelector('#place_edit_form input[name="numBathrooms"]').value;
	let capacity = document.querySelector('#place_edit_form input[name="capacity"]').value;

	request.send(encodeForAjax({ placeID: placeID, title: title, desc: desc, address: address, city: city, country: country, numRooms: numRooms, numBathrooms: numBathrooms, capacity: capacity }));
});

// -----------