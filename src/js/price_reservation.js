'use strict'

let profileForm = document.querySelector('#Pop_UP_Fast_Reservation form');


profileForm.addEventListener('submit', function(event) {
    event.preventDefault();
	
	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_price_reservation.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
        
        let message = JSON.parse(this.responseText).message;
        console.log(message['price'])


            
		switch(message) {        
            case 'false':
				
				
            break
            
            //Retornou algo aplicar DOM para alterar o valor do price per night
            default:

                let price_element=document.getElementById('side_price_per_night')

                price_element.innerHTML=message['price']+'€'
                

			break;
        }
    
    });
    
    let placeID=document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="placeID"]').value
    let check_in_date = document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="check_in_date"]').value
    let check_out_date = document.querySelector('#Pop_UP_Fast_Reservation_Inputs input[name="check_out_date"]').value
  
    request.send(encodeForAjax({placeID:placeID,check_in_date: check_in_date,check_out_date:check_out_date}));
});

// -----------