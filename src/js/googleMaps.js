
'use strict'

//EVENT LISTENER TO CLICK THE MAP AND SET A MARKER: https://www.youtube.com/watch?v=Zxf1mnP5zcw min 26.31.

let map;
let geocoder;
let markersArray = new Array();

//DEFAULT IS PORTO CENTER.
const starting_lat = 41.1579438;
const starting_lng = -8.6291053;
const starting_zoom = 12;
//const iconImageURL=

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

    return true;
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

