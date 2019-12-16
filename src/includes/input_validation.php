<?php

// checks if value is a positive number or not
function validatePosIntValue($value) {
    return preg_match("/^[0-9]+$/", $value);
} 

// checks if value is a number or not
function validateAnyIntValue($value) {
    return preg_match("/^-?[0-9]+$/", $value);
}

// checks if value is a valid date or not
function validateDateValue($value) {
    if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value)) {
        return false;
    }
    $d = DateTime::createFromFormat('Y-m-d', $value);
    return $d && $d->format('Y-m-d') === $value;
}

// checks if a value is a valid username or not (only letters, numbers, - and _, from 3 to 20 chars)
function validateUsernameValue($value) {
    return preg_match("/^[A-Za-z0-9_-]{3,20}$/", $value);
}

// checks if a value is a valid password or not
function validatePasswordValue($value) {
    return preg_match('/^[A-Za-z0-9?+*_!#$%& -]*$/', $value);
}


// checks if a value is a valid description, review, reply, etc or not
function validateTextValue($value) {
    return preg_match('/^[A-Za-z0-9?+*_!#$%,\/;.&\s-]*$/', $value);
}


// checks if a value is a valid location input
function validateLocationValue($value) {
    return preg_match('/^[A-Za-z -]*$/', $value);
}

// checks if a value is a valid email
function validateEmailValue($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function validateGPSCoords($GPSCoordsStr){
    $array_explode=explode(",",$GPSCoordsStr);

    if($array_explode==false||empty($array_explode)){
        return false;
    }
    //GPS COORDS ARE JUST LAT AND LNG
    if(count(($array_explode))>2){
        return false;
    }

    if(!is_numeric($array_explode[0])||!is_numeric($array_explode[1])){
        return false;
    }

    return true;
}


?>