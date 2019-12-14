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

let placeID;
let houseCard;

let removeButtons = document.querySelectorAll('a.remove-button');
for(let i = 0; i < removeButtons.length; i++) {
    let removeButton = removeButtons[i];

    removeButton.addEventListener('click', function(event) {
        event.preventDefault();
        frPopup.style.display = "block";
        let frMessage = document.getElementById("cf-message")
        if(frMessage != null) frMessage.outerHTML = ""

        rowBt.parentNode.insertBefore(confirmMessageRemove(), rowBt);
        confirmBt.style.display = "inline-block";
        placeID = removeButton.getAttribute('data-id');
        houseCard = removeButton.parentElement.parentElement;
    })
}

confirmForm.addEventListener('submit', function(event) {
        event.preventDefault();
        let request = new XMLHttpRequest();
        request.open("POST", "../api/api_delete_place.php", true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.addEventListener('load', function () {
            let message = JSON.parse(this.responseText).message;
            switch(message) {
                case 'yes':
                    // remove house card for that reservation
                    houseCard.remove();
                    
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

                    break;

                case 'no':
                    // console.log("Error on canceling the reservation");
                    break;    

                default:
                    break;
            }

        });

        request.send(encodeForAjax({placeID: placeID}));
});


// ----------------

function confirmMessageRemove() {
    let article = document.createElement("article");
    article.innerHTML = `<p>Are you sure you want to delete this place?</p>`;
    article.setAttribute('id', 'cf-message');
    return article;
}