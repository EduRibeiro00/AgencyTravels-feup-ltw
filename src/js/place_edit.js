





let profileForm = document.querySelector('#place_edit_form form');
//let errorMessage = document.getElementById('profile-form-error');

profileForm.addEventListener('submit', function(event) {
	
	event.preventDefault();
	//errorMessage.style.display = "none";
	//errorMessage.textContent = "";

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_edit.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		//let message = JSON.parse(this.responseText).message;
		
		/*
		switch(message) {
            case 'profile edit completed':
				errorMessage.style.display = "none";
				history.back();
                break;

			case 'user not logged in':
				errorMessage.textContent = 'ERROR: User is not logged in';
                errorMessage.style.display = "block";
				break;

			case 'password not valid':
				errorMessage.textContent = 'The password inserted is invalid.';
				errorMessage.style.display = "block";
				break;

			case 'new passwords dont match':
				errorMessage.textContent = 'The new passwords do not match.';
				errorMessage.style.display = "block";
				break;

			case 'check_password_length':
				errorMessage.textContent = 'The new password needs to be at least 7 characters long.';
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
				break;
		}

		*/
	});
	let placeID=document.querySelector('#place_edit_form input[name="placeID"]').value;
	let title = document.querySelector('#place_edit_form input[name="title"]').value;
	let desc=document.querySelector('#place_edit_form textarea[name="description"]').value;
	let address=document.querySelector('#place_edit_form input[name="address"]').value;
	let city=document.querySelector('#place_edit_form input[name="city"]').value;
	let country=document.querySelector('#place_edit_form input[name="country"]').value;
	let numRooms=document.querySelector('#place_edit_form input[name="numRooms"]').value;
	let numBathrooms=document.querySelector('#place_edit_form input[name="numBathrooms"]').value;
	let capacity=document.querySelector('#place_edit_form input[name="capacity"]').value;
	console.log(capacity)
    request.send(encodeForAjax({placeID:placeID,title:title,desc:desc,address:address,city:city,country:country,numRooms:numRooms,numBathrooms:numBathrooms,capacity:capacity}));
});

// -----------