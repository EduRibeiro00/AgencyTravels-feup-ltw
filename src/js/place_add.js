'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let profileForm = document.querySelector('#place_edit_form form');

profileForm.addEventListener('submit', function (event) {
	
	event.preventDefault();
	
	let request = new XMLHttpRequest();
	
	request.open("POST", "../api/api_place_add.php", true)
	
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
			
	let	formData = new FormData(profileForm);
	
	request.send(formData);
	
});

// -----------