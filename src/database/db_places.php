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

function getAllLocations() {
    $db = Database::instance()->db();
	$stmt = $db->prepare('SELECT * 
                          FROM Location
                          ORDER BY country');
	$stmt->execute();
	return $stmt->fetchAll();
}

function getPlace($placeID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT *
                          FROM Place NATURAL JOIN Location
                          WHERE placeID = ?');
    $stmt->execute(array($placeID));
    $place_info = $stmt->fetch();
    $place_info['images'] = getPlaceImages($placeID);
    return $place_info;
}

function getPlaceImages($placeID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT image
                          FROM Image
                          WHERE placeID = ?');
    $stmt->execute(array($placeID));
    $i = 0;
    return $stmt->fetchAll();
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

// TODO: add checkin and checkout ver imagens tbm
function getFilteredPlacesLoc($nPeople, $rating, $nRooms, $nBathrooms, $city, $country) {
	$db = Database::instance()->db();

	$sqlCity = "%" . $city ."%";
	$sqlCountry = "%" . $country ."%";
    $stmt = $db->prepare('SELECT Place.placeID, title, rating, capacity, numRooms, numBathrooms, gpsCoords, image, IFNULL(nVotes, 0) as nVotes
                          FROM Place LEFT JOIN 
						  (
							  SELECT placeID, count(*) AS nVotes 
							  FROM review NATURAL JOIN Reservation 
							  GROUP BY placeID
						  ) AS CV ON Place.placeID = CV.placeID -- This subquery gives the number of Votes
						  NATURAL JOIN Location
						  NATURAL JOIN Image
						--   WHERE country LIKE ? AND city LIKE ?
						  WHERE capacity >= ?
						  AND rating >= ?
						  AND numRooms >= ?
						  AND numBathrooms >= ?
						  AND city LIKE ? AND country LIKE ?
						  GROUP BY Place.placeID
						  ');
	$stmt->execute(array($nPeople, $rating, $nRooms, $nBathrooms, $sqlCity, $sqlCountry));
	return $stmt->fetchAll();
}

function getFilteredPlacesLocDates($nPeople, $rating, $nRooms, $nBathrooms, $city, $country, $checkin, $checkout) {
	$db = Database::instance()->db();

	$sqlCity = "%" . $city ."%";
	$sqlCountry = "%" . $country ."%";
    $stmt = $db->prepare('SELECT Place.placeID, title, rating, capacity, numRooms, numBathrooms, gpsCoords, image, IFNULL(nVotes, 0) as nVotes, pricePerNight as price
                          FROM Place LEFT JOIN 
						  (
							  SELECT placeID, count(*) AS nVotes 
							  FROM review NATURAL JOIN Reservation 
							  GROUP BY placeID
						  ) AS CV ON Place.placeID = CV.placeID -- This subquery gives the number of Votes
						  NATURAL JOIN Location
						  NATURAL JOIN Image
						  NATURAL JOIN 
						  ( -- TODO: por em view talvez??
							  SELECT Place.placeID, pricePerNight
							  FROM Place NATURAL JOIN Availability
							  WHERE startDate <= ? AND endDate >= ?
						  )
						--   WHERE country LIKE ? AND city LIKE ?
						  WHERE capacity >= ?
						  AND rating >= ?
						  AND numRooms >= ?
						  AND numBathrooms >= ?
						  AND city LIKE ? AND country LIKE ?
						  GROUP BY Place.placeID
						  ');
	$stmt->execute(array($checkin, $checkout, $nPeople, $rating, $nRooms, $nBathrooms, $sqlCity, $sqlCountry));
	return $stmt->fetchAll();
}

function getFilteredPlacesDates($nPeople, $rating, $nRooms, $nBathrooms, $location, $checkin, $checkout) {
	$db = Database::instance()->db();
	$sqlLocation = "%" . $location ."%";
    $stmt = $db->prepare('SELECT Place.placeID, title, rating, capacity, numRooms, numBathrooms, gpsCoords, image, IFNULL(nVotes, 0) as nVotes, pricePerNight as price
                          FROM Place LEFT JOIN 
						  (
							  SELECT placeID, count(*) AS nVotes 
							  FROM review NATURAL JOIN Reservation 
							  GROUP BY placeID
						  ) AS CV ON Place.placeID = CV.placeID -- This subquery gives the number of Votes
						  NATURAL JOIN Location
						  NATURAL JOIN Image
						  NATURAL JOIN 
						  ( -- TODO: por em view talvez??
							  SELECT Place.placeID, pricePerNight
							  FROM Place NATURAL JOIN Availability
							  WHERE startDate <= ? AND endDate >= ?
						  )
						--   WHERE country LIKE ? AND city LIKE ?
						  WHERE capacity >= ?
						  AND rating >= ?
						  AND numRooms >= ?
						  AND numBathrooms >= ?
						  AND (city LIKE ? OR country LIKE ?)
						  GROUP BY Place.placeID
						  ');
	$stmt->execute(array($checkin, $checkout, $nPeople, $rating, $nRooms, $nBathrooms, $sqlLocation, $sqlLocation));
	return $stmt->fetchAll();
}

// TODO: ver se assim ou mesmo com ifs lá dentro da outra
function getFilteredPlaces($nPeople, $rating, $nRooms, $nBathrooms, $location) {
	$db = Database::instance()->db();
	$sqlLocation = "%" . $location ."%";
    $stmt = $db->prepare('SELECT Place.placeID, title, rating, capacity, numRooms, numBathrooms, gpsCoords, image, IFNULL(nVotes, 0) as nVotes
                          FROM Place LEFT JOIN (select placeID, count(*) as nVotes from review NATURAL JOIN Reservation GROUP BY placeID) AS CV ON Place.placeID = CV.placeID -- This subquery gives the number of Votes
						  NATURAL JOIN Location
						  NATURAL JOIN Image
						--   WHERE country LIKE ? AND city LIKE ?
						  WHERE capacity >= ?
						  AND rating >= ?
						  AND numRooms >= ?
						  AND numBathrooms >= ?
						  AND (city LIKE ? OR country LIKE ?)
						  GROUP BY Place.placeID
						  ');
	$stmt->execute(array($nPeople, $rating, $nRooms, $nBathrooms, $sqlLocation, $sqlLocation));
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
    $stmt = $db->prepare('SELECT round(avg(pricePerNight)) as avg_price
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

function insertImageForPlace($placeID, $image) {
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

function getCompatibleAvailability($placeID, $check_in_date, $check_out_date){
    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT pricePerNight as price
                          FROM Availability Natural Join Place
                          WHERE placeID = ? AND date(startDate)>= date(?) AND date(?)<=date(endDate)');
    
    $stmt->execute(array($placeID,$check_in_date,$check_out_date));
    
    return $stmt->fetch();
}

function getHouseComments($place_id) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT comment, Review.stars as stars, User.username as username, User.userID as userID, image, Review.date as date, Place.placeID as placeID
                          FROM Place NATURAL JOIN Reservation NATURAL JOIN Review JOIN User on Reservation.touristID=User.userID JOIN Image on User.userID = Image.userID
						  WHERE Place.placeID = ?');
    $stmt->execute(array($place_id));
    return $stmt->fetchAll();
}


function updatePlaceInfo($placeID, $title, $desc, $address, $city, $country, $numRooms, $numBathrooms, $capacity){
    $db = Database::instance()->db();
    try {
        $stmt = $db->prepare('UPDATE Place
                              SET title = ?,
                                  address = ?,
                                  description = ?,
                                  capacity = ?,
                                  numRooms = ?,
                                  numBathrooms = ? 
                               WHERE placeID = ?
                               ' 
                            );

     $stmt->execute(array($title, $address, $desc, $capacity, $numRooms, $numBathrooms, $placeID));
    }

    catch (PDOException $e) {
        return $e->getMessage();
    }

    //TODO:UPDATE LOCATION NOT IMPLEMENTED

    return true;
}

?>
