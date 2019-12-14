
'use strict'

//EVENT LISTENER TO CLICK THE MAP AND SET A MARKER: https://www.youtube.com/watch?v=Zxf1mnP5zcw min 26.31.

//DEFAULT IS PORTO CENTER.
//const iconImageURL=
let starting_lat = 41.1579438;
let starting_lng = -8.6291053;
let starting_zoom = 12;
let map;
let geocoder;
let markersArray = new Array();


//THEN RETREVIE THE INFORMATION FROM THE LOCATION DROPDOWN
let addressField = document.getElementById('form_place_address');
let LocationInfo = document.getElementById('location');
let GPSCoordsDom = document.getElementById('PlaceGPSCoords');

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

function parseRawParagraphWithCoordinates(string_raw){

    let index_start_numbers=string_raw.indexOf(':');
    let str_return;
    //To increment
    index_start_numbers++
    str_return=string_raw.substr(index_start_numbers);
    return str_return;
}

function initGoogleMapsServices() {

    let raw_paragraph_string=GPSCoordsDom.innerHTML;
    let string_parsed=parseRawParagraphWithCoordinates(raw_paragraph_string);
    starting_lat=Number(get_lat(string_parsed));
    starting_lng=Number(get_long(string_parsed));

    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: starting_lat, lng: starting_lng },
        zoom: starting_zoom
    });

    addMarker(string_parsed);

    if (map == null || geocoder == null) {
        return false;
    }
    //IF ITS EDIT MENU. THEN WE SHOULD FOCUS THE MAP ON THIS PLACE. NOTICE ADD MARKER ALSO FOCUES THE MAP ON THE PLACE
    return true;
}


//NOTE THE DIFFERENCE TO THE HOUSE LIST. IN HOUSE LIST WE RECEIVE AN ARRAY WITH INFORMATION HERE ITS JUST A STRING
//COORDINATES COME IN FORMAT STRING LAT , LNG
function addMarker(string_with_geocoordinates) {

    let lat;
    let long;

    lat = Number(get_lat(string_with_geocoordinates));
    long = Number(get_long(string_with_geocoordinates));

    let marker = new google.maps.Marker({
        position: { lat: lat, lng: long },
        map: map
    });

    markersArray.push(marker);

    //SET THE FOCUS TO THE LAST HOUSE IN THE LIST
    let latLng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng());
    map.setCenter(latLng);
}
