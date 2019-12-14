
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
let starting_lat = 41.1579438;
let starting_lng = -8.6291053;
let starting_zoom = 12;
let map;
let geocoder;
let markersArray = new Array();


//THEN RETREVIE THE INFORMATION FROM THE LOCATION DROPDOWN
let addressField = document.getElementById('form_place_address');
let LocationInfo = document.getElementById('location');
let GPSCoordsDom = document.getElementById('form_place_GPS');

addressField.addEventListener('blur', handlerChangeAddress);


function handlerChangeAddress(event) {
    let country = getCountryFromRaw(LocationInfo.options[LocationInfo.selectedIndex].text);
    let city = getCityFromRaw(LocationInfo.options[LocationInfo.selectedIndex].text);
    //event.target.value to retrievie address

    let coordinates;

    let stringParsedForGoogleMaps = event.target.value + ',' + city + ',' + country;
    //CALLBACK??? IT WORKS :) 
    codeAddress(stringParsedForGoogleMaps, function(coords){
        coordinates=coords;
        GPSCoordsDom.value=coordinates;
        
    });

}



//DEFAULT IS PORTO CENTER.
//const iconImageURL=

window.addEventListener('load', initGoogleMapsServices);

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
    
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: starting_lat, lng: starting_lng },
        zoom: starting_zoom
    });
    geocoder = new google.maps.Geocoder();
    
    if (map == null || geocoder == null) {
        return false;
    }
    
    if(GPSCoordsDom.value!=''){
        addMarker(GPSCoordsDom.value);
    }
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

    //SET THE FOCUS TO THE LAST HOUSE IN THE LIST
    let latLng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng());
    map.setCenter(latLng);
}

function codeAddress(address, callback) {
    
    geocoder.geocode({ 'address': address }, function (results, status) {
        
        let return_code;
        let lat;
        let lng;
        if (status == 'OK') {
            //ADD A MARKER WHEN I GEOCODE
            map.setCenter(results[0].geometry.location);
            let marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            let myLatLng = marker.getPosition();
            lat = myLatLng.lat();
            lng = myLatLng.lng();
            return_code = String(lat) + ',' + String(lng);
            callback(return_code);
        }
        else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });

 return true;
}


