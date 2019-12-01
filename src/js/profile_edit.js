let profileForm = document.querySelector('#profile-form form');
let errorMessage = document.getElementById('profile-form-error');

profileForm.addEventListener('submit', function(event) {
    event.preventDefault();
	errorMessage.style.display = "none";
	errorMessage.textContent = "";

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_profile_edit.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
		let message = JSON.parse(this.responseText).message;
		
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
	});

	let userID = document.querySelector('#profile-form input[name="userID"]').value;
	let username = document.querySelector('#profile-form input[name="username"]').value;
	let password = document.querySelector('#profile-form input[name="password"]').value;
	let newPassword = document.querySelector('#profile-form input[name="new-password"]').value;
	let confNewPassword = document.querySelector('#profile-form input[name="conf-new-password"]').value;
	let name = document.querySelector('#profile-form input[name="name"]').value;
	let email = document.querySelector('#profile-form input[name="email"]').value;
	let bio = document.querySelector('#profile-form textarea[name="bio"]').value;
	let birthDate = document.querySelector('#profile-form input[name="birthDate"]').value;
	let gender = document.querySelector('#profile-form input[name="gender"]:checked').value;
    let loc = document.querySelector('#profile-form input[name="location"]').value;

    request.send(encodeForAjax({userID: userID, username: username, password: password, newPassword: newPassword, confNewPassword: confNewPassword, name: name, email: email, bio: bio, birthDate: birthDate, gender: gender, location: loc}));
});

// -----------