
'use strict'

//EVENT LISTENER TO CLICK THE MAP AND SET A MARKER: https://www.youtube.com/watch?v=Zxf1mnP5zcw min 26.31.


function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

function getCountryFromRaw(string_raw){
    
    let indexSeparator=string_raw.indexOf('-');
    indexSeparator--;
    let str_country=string_raw.slice(0,indexSeparator);
    //REMOVE WHITESPACE
    return str_country.trim();
}

function getCityFromRaw(string_raw){
    
    let indexSeparator=string_raw.indexOf('-');
    indexSeparator++;
    let str_city=string_raw.substr(indexSeparator);
    //REMOVE WHITESPACE
    return str_city.trim();
}

let map;
let geocoder;
let markersArray = new Array();
let gpsCoords=new Array();


let locationDomElement=document.getElementById('location_place_holder');
let location_raw_str=locationDomElement.getAttribute('data-location');
let city=getCityFromRaw(location_raw_str);
let country=getCountryFromRaw(location_raw_str);



//DEFAULT IS PORTO CENTER.
const starting_lat = 41.1579438;
const starting_lng = -8.6291053;
const starting_zoom = 12;
//const iconImageURL=

window.addEventListener('load', initGoogleMapsServices);

function setHTTPRequestToRetrievePlaceCoords(){
    
    let request = new XMLHttpRequest();

	request.open("POST", "../api/api_get_places_location.php", true)
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

	request.addEventListener('load', function() {
        let message = JSON.parse(this.responseText).message;
        
        for(let i=0;i<message.length;i++){
            //EACH POSTION OF THIS ARRAY ARE PAIRS LAT LNG
            gpsCoords.push(message[i].gpsCoords);
        }
        //TODO:PARSE THE COORDS ARRAY

    });
    //NO NEED TO SEND ENCONDED FOR THE AJAX EMPTY REQUEST
    request.send(encodeForAjax({city: city, country: country}));

    return true;
}

function get_lat(stringWithCoords) {
    let pos_separator = stringWithCoords.indexOf(',');
    //POS BEFORE THE COMMA---> TOSLICE
    pos_separator--;
    let lat = stringWithCoords.slice(0, pos_separator);
    return lat;
}

function get_long(stringWithCoords) {
    let pos_separator = stringWithCoords.indexOf(',');
    //NEXT POS AFTER THE COMMA
    pos_separator++
    let long = stringWithCoords.substr(pos_separator);
    return long;
}

function initGoogleMapsServices() {

    if (setHTTPRequestToRetrievePlaceCoords() == true) {
        console.log(gpsCoords);
        /*
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: starting_lat, lng: starting_lng },
            zoom: starting_zoom
        });
        geocoder = new google.maps.Geocoder();

        if (map == null || geocoder == null) {
            return false;
        }
        */
        return true;
    }
}
//COORDINATES COME IN FORMAT STRING LAT , LNG
function addMarker(array_with_geocoordinates) {

    for (let i = 0; i < array_with_geocoordinates.length; i++) {
        let lat;
        let long;

        lat = get_lat(array_with_geocoordinates[i]);
        long = get_long(array_with_geocoordinates[i]);

        let coords = { lat: lat, long: long };

        let marker = new google.maps.Marker({
            position: coords,
            map: map,
        });

        markersArray.push(marker);
    }
}

function codeAddress() {
    let address = document.getElementById('address').value;

    geocoder.geocode({ 'address': address }, function (results, status) {


        if (status == 'OK') {
            //ADD A MARKER WHEN I GEOCODE
            map.setCenter(results[0].geometry.location);

            let marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            }
            );
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

