'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let removeButtons = document.querySelectorAll('a.remove-button');
for(let i = 0; i < removeButtons.length; i++) {
    let removeButton = removeButtons[i];

    removeButton.addEventListener('click', function(event) {
        event.preventDefault();
        let placeID = removeButton.getAttribute('data-id');
        let request = new XMLHttpRequest();
        request.open("POST", "../api/api_delete_place.php", true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.addEventListener('load', function () {
            let message = JSON.parse(this.responseText).message;
            switch(message) {
                case 'yes':
                    // remove house card for that reservation
                    let houseCard = removeButton.parentElement.parentElement;
                    houseCard.remove();
                    
                    // adjust footer
                    let html = document.documentElement;
                    let body = document.body;
                    let bodyHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight) + 50;
                
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
}


// ----------------