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

function getLocations($location) {
	$val = "%" . $location ."%";
	$db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * 
						FROM Location
						WHERE country LIKE ? OR city LIKE ?');
	$stmt->execute(array($val, $val));
	return $stmt->fetchAll();
}


function getAllLocations() {
    $db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * 
                          FROM Location
                          ORDER BY country');
	$stmt->execute();
	return $stmt->fetchAll();
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

function getPlace($place_id) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT *
                            FROM Place NATURAL JOIN Location
                            WHERE placeID=?');
    $stmt->execute(array($place_id));
    return $stmt->fetch();
}

function getRandomPlacesFromCity($locationID, $number) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT *
                          FROM Place NATURAL JOIN Image
                          WHERE locationID = ?
                          GROUP BY placeID
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

function getAveragePrice($placeID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT round(avg(pricePerDay)) as avg_price
                          FROM Availability
                          WHERE placeID = ?');
	$stmt->execute(array($placeID));
	return $stmt->fetch();
}

function getTopDestinations() {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT *, (SELECT image
                                     FROM Place NATURAL JOIN Image
                                     WHERE locationID = TopDestinations.locationID) as image
                          FROM TopDestinations
                          LIMIT 4');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getTrendingDestinations() {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT *, (SELECT image
                                     FROM Place NATURAL JOIN Image
                                     WHERE locationID = TrendingDestinations.locationID) as image
                          FROM TrendingDestinations
                          LIMIT 4');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getRandomImagesFromCity($locationID, $number) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT image
                         FROM Place NATURAL JOIN Image
                         WHERE locationID = ?
                         LIMIT ?');
    $stmt->execute(array($locationID, $number));
    return $stmt->fetchAll();
}

function insertImageForPlace($place, $image) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('INSERT INTO Image (placeID, image)
                          VALUES(?, ?)'
                        );
    $stmt->execute(array($placeID, $image));
}

function getPlaceOwnerName($placeID){
    
    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT User.name as name
                          FROM Place JOIN User ON Place.ownerID=User.userID
                          WHERE placeID = ?');
    $stmt->execute(array($placeID));
    
    return $stmt->fetch();
}

function getCompatibleAvailability($placeID,$check_in_date,$check_out_date){


    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT pricePerDay as price
                          FROM Availability Natural Join Place
                          WHERE placeID = ? AND date(startDate)>= date(?) AND date(?)<=date(endDate)');
    
    $stmt->execute(array($placeID,$check_in_date,$check_out_date));
    
    return $stmt->fetch();
}
function getHouseComments($place_id) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT comment, Review.stars as stars, User.name as name, Review.date as date
                            FROM Place NATURAL JOIN Reservation NATURAL JOIN Review JOIN User on Reservation.touristID=User.userID 
							WHERE placeID=?');
    $stmt->execute(array($place_id));
    return $stmt->fetchAll();
}

?>
