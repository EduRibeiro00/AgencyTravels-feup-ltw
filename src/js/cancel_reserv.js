'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let confirmForm = document.getElementById('cf-confirmation')
let frPopup = document.getElementById('cf-popup')
let cancelBt = document.getElementById('cf-cancel-button')
let confirmBt = document.getElementById('cf-confirm-button')

let rowBt = document.querySelector('#cf-confirmation .row')


cancelBt.addEventListener('click', function(){
	frPopup.style.display = "none"
})

confirmBt.addEventListener('click', function(){
	frPopup.style.display = "none"
})

window.addEventListener('click', function(event){
	if (event.target == frPopup)
		frPopup.style.display = "none";
});

let reservationID;
let reservationCard;

let cancelButtons = document.querySelectorAll('a.cancel-button');
for(let i = 0; i < cancelButtons.length; i++) {
    let cancelButton = cancelButtons[i];

    cancelButton.addEventListener('click', function(event) {
        event.preventDefault();
        frPopup.style.display = "block";
        let frMessage = document.getElementById("cf-message")
        if(frMessage != null) frMessage.outerHTML = ""

        rowBt.parentNode.insertBefore(confirmMessageCancel(), rowBt);
        confirmBt.style.display = "inline-block";
        reservationID = cancelButton.getAttribute('data-id');
        reservationCard = cancelButton.parentElement.parentElement;
    })
}

confirmForm.addEventListener('submit', function(event) {
    event.preventDefault();
    let request = new XMLHttpRequest();
    request.open("POST", "../api/api_cancel_reserv.php", true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', function () {
        let message = JSON.parse(this.responseText).message;
        switch(message) {
            case 'token error':
                break;

            case 'yes':
                // remove card for that reservation
                reservationCard.remove();
                
                // adjust footer
                let body = document.body;
                let bodyHeight = body.scrollHeight;
            
                if(bodyHeight < screen.height) {
                    let footer = document.querySelector('body > footer');
                    footer.style.position = "fixed";
                    footer.style.bottom = "0";
                    footer.style.left = "0";
                    footer.style.right = "0";
                }

                showDialog('Reservation successfully canceled');
                
                break;

            case 'no':
                showDialog("Error on canceling the reservation (invalid input or you may not have permission");
                break;

            case "user not logged in":
                showDialog("User is not logged in");
                break;

            default:
                break;
        }
    });

    let csrf = event.target.querySelector('input[name="csrf"]').value;

    request.send(encodeForAjax({csrf: csrf, reservationID: reservationID}));
});


// -----------------

function confirmMessageCancel() {
    let article = document.createElement("article");
    article.innerHTML = `<p>Are you sure you want to cancel this reservation?</p>`;
    article.setAttribute('id', 'cf-message');
    return article;
}