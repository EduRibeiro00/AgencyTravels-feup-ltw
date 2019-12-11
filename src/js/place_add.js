'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let profileForm = document.querySelector('#place_edit_form form');
let errorMessage = document.getElementById('place-form-error');

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_add.php", true)

	request.addEventListener('load', function () {
		console.log(this.responseText);
		let message = JSON.parse(this.responseText).message;


		switch (message) {
			case 'true':
				history.back();
				break;

			default:
				errorMessage.textContent = "The username cannot have spaces.";
				errorMessage.style.display = "block";
				
				break;
		}

	});

	let formData = new FormData(profileForm);

	request.send(formData);

});

// -----------