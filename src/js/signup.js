'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let profileForm = document.querySelector('#profile-form form');
let errorMessage = document.getElementById('profile-form-error');

profileForm.addEventListener('submit', function(event) {
    event.preventDefault();
	errorMessage.style.display = "none";
	errorMessage.textContent = "";

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_signup.php", true);

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		
		switch(message) {
            case 'signup completed':
				errorMessage.style.display = "none";
				location.reload(true);
                break;

			case 'invalid image':
				errorMessage.textContent = "The image uploaded is invalid; please choose another one.";
				errorMessage.style.display = "block";
				break;

			case 'user already logged in':
				errorMessage.textContent = 'ERROR: User already logged in';
                errorMessage.style.display = "block";
				break;

			case 'check_password_length':
				errorMessage.textContent = 'The password needs to be at least 7 characters long.';
                errorMessage.style.display = "block";
				break;
			
			case 'User.email':
				errorMessage.textContent = "The email inserted is already in use.";
                errorMessage.style.display = "block";
				break;

			case 'User.username':
				errorMessage.textContent = "The username inserted is already in use.";
                errorMessage.style.display = "block";
				break;

			case 'check_username_no_spaces' :
				errorMessage.textContent = "The username cannot have spaces.";
				errorMessage.style.display = "block";
				break;

			default:
				// console.log(message);
				break;
		}
	});

	let	formData = new FormData(profileForm);
	formData.append('hasFile', document.querySelector('#img-upload input[type="file"]').getAttribute('data-hasFile'));

    request.send(formData);
});

// -----------
