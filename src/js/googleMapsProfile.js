
'use strict'


let locationDomElement = document.getElementById('location');
let location_raw_str = locationDomElement.innerText;

function initGoogleMapsServices() {
    let parseStr=parseRawString(location_raw_str);
    let country=getCountryFromRaw(parseStr);
    let city=getCityFromRaw(parseStr);
    let addressStr=city+','+country;

    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: starting_lat, lng: starting_lng },
        zoom: starting_zoom
    });
    geocoder = new google.maps.Geocoder();

    if (map == null || geocoder == null) {
        return false;
    }
    codeAddress(addressStr);
    return true;
    
}

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}


function parseRawString(rawStr){
    let strRet;
    let indexSeparator= rawStr.indexOf(':');
    indexSeparator++;
    strRet=rawStr.substr(indexSeparator);
    return strRet;
}

function getCountryFromRaw(string_raw) {
    string_raw=string_raw.trim();
    let indexSeparator = string_raw.indexOf(',');
    indexSeparator;
    let str_country = string_raw.slice(0, indexSeparator);
    //REMOVE WHITESPACE
    return str_country.trim();
}

function getCityFromRaw(string_raw) {

    let indexSeparator = string_raw.indexOf(',');
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


//FOR EDIT AND ADD MENU THERE ISNT THIS PLACE HOLDER


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

function codeAddress(address) {

    geocoder.geocode({ 'address': address }, function (results, status) {

        let return_code;
        let lat;
        let lng;

        //MUST BE ONLY ONE MARKER ACTIVE IN THIS PAGES. Remove on update address. ALSO used in the add menu, when we update each time the place    
        if (status == 'OK') {
            //ADD A MARKER WHEN I GEOCODE
            map.setCenter(results[0].geometry.location);
            let marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            markersArray.push(marker);
            let myLatLng = marker.getPosition();
            lat = myLatLng.lat();
            lng = myLatLng.lng();
            return_code = String(lat) + ',' + String(lng);
        }
        else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });

    return true;
}



