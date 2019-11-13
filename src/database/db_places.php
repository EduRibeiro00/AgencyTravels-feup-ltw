<?php
include_once('../database/db_connection.php');


// -------------------
// not being used rn vv


function getRandomPlacesFromCountry($country, $number) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * 
                          FROM Place NATURAL JOIN Location
                          WHERE country = ?
                          ORDER BY random()
                          LIMIT ?');
    $stmt->execute(array($country, $number));
    return $stmt->fetchAll();
}


function getRandomCountry() {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT country 
                          FROM Location
                          ORDER BY random()
                          LIMIT 1');
    $stmt->execute();
    return $stmt->fetch();
}


function getRandomPlacesRandomCountry($number) {
    $country = getRandomCountry();
    return getRandomPlacesFromCountry($country['country'], $number);
}

function getRandomPlacesRandomCity($number) {
    $city = getRandomCity();
    return getRandomPlacesFromCity($city['locationID'], $number);
}

// ----------------
// currently used vv

function getRandomPlacesFromCity($locationID, $number) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * 
                          FROM Place 
                          WHERE locationID = ?
                          ORDER BY random()
                          LIMIT ?');
    $stmt->execute(array($locationID, $number));
    return $stmt->fetchAll();
}

function getRandomCity() {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT * 
                          FROM Location
                          ORDER BY random()
                          LIMIT 1');
    $stmt->execute();
    return $stmt->fetch();
}

?>
