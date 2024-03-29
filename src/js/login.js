'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

//// Login
let popup = document.getElementById('login-popup');
let login = document.getElementById('loginlink');
let loginForm = document.getElementById('login-form');
let loginMessage = document.getElementById('login-message');
let loginCross = document.getElementById('login-form-cross-login');

login.addEventListener('click', function(){
	popup.style.display = "block";
})

window.addEventListener('click', function(event){
	if (event.target == popup) {
        loginForm.reset();
        popup.style.display = "none";
        loginMessage.textContent = "";
        loginMessage.style.display = "none";
    }
});


loginCross.addEventListener('click', function(event) {
    loginForm.reset();
    loginMessage.textContent = "";
    loginMessage.style.display = "none";
})



loginForm.addEventListener('submit', function(event) {
    event.preventDefault();
	loginMessage.style.display = "none";
    loginMessage.textContent = "";

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_login.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
        let message = JSON.parse(this.responseText).message;

		switch(message) {
            case 'token error':
                break;

            case 'values not defined':
                loginMessage.textContent = "Please write a valid username and password.";
                loginMessage.style.display = "block";
                break;

			case 'user already logged in':
				loginMessage.textContent = 'ERROR: User already logged in';
                loginMessage.style.display = "block";
				break;

			case 'invalid credentials':
				loginMessage.textContent = 'The username and password combination are invalid.'
                loginMessage.style.display = "block";
				break;
			
			case 'login successful':
                loginMessage.style.display = "none";
                popup.style.display = "none";
                location.reload(true);
				break;

            default:
				break;
		}
	});

    let username = document.querySelector('input[name="username"]').value;
    let password = document.querySelector('input[name="password"]').value;

    let csrf = event.target.querySelector('input[name="csrf"]').value;

    request.send(encodeForAjax({csrf: csrf, username: username, password: password}));
});

// -------------------
