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

	request.open("POST", "../api/api_place_edit.php", true)
	
	request.addEventListener('load', function () {
		console.log(this.responseText);
		let message = JSON.parse(this.responseText).message;
		console.log(message)

		
		switch(message) {
            case 'true':
				//errorMessage.style.display = "none";
                break;
			
            default:
				history.back();
				break;
		}

	});
	let	formData = new FormData(profileForm);
	
	request.send(formData);
});

// -----------