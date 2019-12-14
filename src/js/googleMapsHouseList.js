
'use strict'

//EVENT LISTENER TO CLICK THE MAP AND SET A MARKER: https://www.youtube.com/watch?v=Zxf1mnP5zcw min 26.31.


function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

function getCountryFromRaw(string_raw) {

    let indexSeparator = string_raw.indexOf('-');
    indexSeparator--;
    let str_country = string_raw.slice(0, indexSeparator);
    //REMOVE WHITESPACE
    return str_country.trim();
}

function getCityFromRaw(string_raw) {

    let indexSeparator = string_raw.indexOf('-');
    indexSeparator++;
    let str_city = string_raw.substr(indexSeparator);
    //REMOVE WHITESPACE
    return str_city.trim();
}
//DEFAULT IS PORTO CENTER.
let starting_lat = 41.1579438;
let starting_lng = -8.6291053;
let starting_zoom = 12;
let markersArray = new Array();
let gpsCoordsArray = new Array();

let map;
let geocoder;


let locationDomElement = document.getElementById('location_place_holder');

//FOR EDIT AND ADD MENU THERE ISNT THIS PLACE HOLDER

let location_raw_str = locationDomElement.getAttribute('data-location');
let city = getCityFromRaw(location_raw_str);
let country = getCountryFromRaw(location_raw_str);
//MUST BE GLOBAL
let arrayWithHouseCards = document.querySelectorAll('article.row');
let array_aux = new Array();


//const iconImageURL=


function setHTTPRequestToRetrievePlaceCoords() {

    let request = new XMLHttpRequest();

    request.open("POST", "../api/api_get_places_location.php", true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    request.addEventListener('load', function () {
        gpsCoordsArray = [];

        let message = JSON.parse(this.responseText).message;

        for (let i = 0; i < message.length; i++) {
            //EACH POSTION OF THIS ARRAY ARE PAIRS LAT LNG
            gpsCoordsArray.push(message[i].gpsCoords);
        }
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: starting_lat, lng: starting_lng },
            zoom: starting_zoom
        });
        geocoder = new google.maps.Geocoder();

        if (map == null || geocoder == null) {
            return false;
        }

        addMarker(gpsCoordsArray);
        //TODO:PARSE THE COORDS ARRAY

    });
    //NO NEED TO SEND ENCONDED FOR THE AJAX EMPTY REQUEST
    request.send(encodeForAjax({ city: city, country: country }));

    return true;
}


function installHoverEventListeners() {

    for (let i = 0; i < arrayWithHouseCards.length; i++) {
        array_aux.push(arrayWithHouseCards[i]);
        arrayWithHouseCards[i].addEventListener('mouseover', function (event) {
            cleanAllOtherShadowEffects();
            //Find the position in the index
            let pos = array_aux.indexOf(event.currentTarget);
            //SET THE FOCUS TO THE LAST HOUSE IN THE LIST
            let latLng = new google.maps.LatLng(markersArray[pos].getPosition().lat(), markersArray[pos].getPosition().lng());
            map.setCenter(latLng);
            map.setZoom(16);
        });
        //RESET THE ZOOM WHEN MOUSE IF OFF THE CARD
        arrayWithHouseCards[i].addEventListener('mouseout', function () {
            map.setZoom(12);
        });
    }

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

    let result = setHTTPRequestToRetrievePlaceCoords();
    installHoverEventListeners();

    if (result == true) {
        return true;
    }

    return false;

}
//COORDINATES COME IN FORMAT STRING LAT , LNG
function addMarker(array_with_geocoordinates) {

    for (let i = 0; i < array_with_geocoordinates.length; i++) {
        let lat;
        let long;

        lat = Number(get_lat(array_with_geocoordinates[i]));
        long = Number(get_long(array_with_geocoordinates[i]));

        let marker = new google.maps.Marker({
            position: { lat: lat, lng: long },
            map: map
        });

        markersArray.push(marker);

        installMarkerHandler(marker);
    }
    //SET THE FOCUS TO THE LAST HOUSE IN THE LIST
    let latLng = new google.maps.LatLng(markersArray[markersArray.length - 1].getPosition().lat(), markersArray[markersArray.length - 1].getPosition().lng());
    map.setCenter(latLng);
}


function installMarkerHandler(marker) {

    const hoverClassName = 'mapEffect';

    google.maps.event.addListener(marker, 'click', function (event) {
        // do something with this marker ...

        cleanAllOtherShadowEffects();
        let index_marker = markersArray.indexOf(marker);
        let latLng = new google.maps.LatLng(markersArray[index_marker].getPosition().lat(), markersArray[index_marker].getPosition().lng());
        map.setCenter(latLng);

        let classNameOriginal = arrayWithHouseCards[index_marker].className;
        let classNameChange = classNameOriginal + ' ' + hoverClassName;
        arrayWithHouseCards[index_marker].className = classNameChange;

    });

}
//CLEAN ALL THE PREVIOUS SHADDOW EFFECTS
function cleanAllOtherShadowEffects() {

    const classNameOriginal = 'row card';
    for (let i = 0; i < markersArray.length; i++) {
        arrayWithHouseCards[i].className = classNameOriginal;
    }
}

