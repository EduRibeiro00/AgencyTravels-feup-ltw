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
                case 'token error':
                    break;

                case 'yes':
                    // remove house card for that reservation
                    houseCard.remove();

                    showDialog("Place successfully removed");

                    break;

                case 'no':
                    showDialog("An error ocurred (some inputs may be invalid). Please try again.");
                    break;    

                case "not owner":
                    showDialog("The logged in user is not the owner of the house");
                    break;

                default:
                    break;
            }

        });

        let csrf = event.target.querySelector('input[name="csrf"]').value;

        request.send(encodeForAjax({csrf: csrf, placeID: placeID}));


        // aqui se encontra o codigo pertencente a REST API das casas,
	    // que faz um DELETE request. Mais informacoes no ficheiro rest_place.php. 

        // request.open("DELETE", "../rest/rest_place.php?" + encodeForAjax({csrf: csrf, placeID: placeID}), true);

        // request.addEventListener('load', function () {
        //     let message = this.status;

        //     switch(message) {
        //         case 403:
        //             break;

        //         case 204:
        //             // remove house card for that reservation
        //             houseCard.remove();

        //             showDialog("Place successfully removed");
        //             break;

        //         case 400:
        //             showDialog("An error ocurred (some inputs may be invalid). Please try again.");
        //             break;

        //         case 401:
        //             showDialog("The logged in user is not the owner of the house");
        //             break;

        //         default:
        //             break;
        //     }

        // });

        // request.send();
});


// ----------------

function confirmMessageRemove() {
    let article = document.createElement("article");
    article.innerHTML = `<p>Are you sure you want to delete this place?</p>`;
    article.setAttribute('id', 'cf-message');
    return article;
}