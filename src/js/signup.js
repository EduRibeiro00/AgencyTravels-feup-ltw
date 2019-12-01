let profileForm = document.querySelector('#profile-form form');
let errorMessage = document.getElementById('profile-form-error');

profileForm.addEventListener('submit', function(event) {
    event.preventDefault();
	errorMessage.style.display = "none";
	errorMessage.textContent = "";

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_signup.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
        let message = JSON.parse(this.responseText).message;

		switch(message) {
            case 'signup completed':
				errorMessage.style.display = "none";
				location.reload(true);
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
				break;
		}
	});

	let username = document.querySelector('#profile-form input[name="username"]').value;
	let password = document.querySelector('#profile-form input[name="password"]').value;
	let name = document.querySelector('#profile-form input[name="name"]').value;
	let email = document.querySelector('#profile-form input[name="email"]').value;
	let bio = document.querySelector('#profile-form textarea[name="bio"]').value;
	let birthDate = document.querySelector('#profile-form input[name="birthDate"]').value;
	let gender = document.querySelector('#profile-form input[name="gender"]:checked').value;
    let loc = document.querySelector('#profile-form input[name="location"]').value;

    request.send(encodeForAjax({username: username, password: password, name: name, email: email, bio: bio, birthDate: birthDate, gender: gender, location: loc}));
});

// -----------