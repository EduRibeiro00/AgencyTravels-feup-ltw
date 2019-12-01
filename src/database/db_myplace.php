<?php
include_once('../database/db_connection.php');


function get_house_title($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT title
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_rating($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT rating
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_description($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT description
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_numRooms($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT numRooms
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}


function get_house_capacity($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT capacity
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_numBathrooms($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT numBathrooms
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_address($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT address
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_gpsCoords($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT gpsCoords
                            FROM Place
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_comments($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT comment,Review.stars as stars, User.name as name, Review.date as date,Location.city as city,Location.country as country
                            FROM Place NATURAL JOIN Reservation NATURAL JOIN Review JOIN User on Reservation.touristID=User.userID Join Location on User.locationID=Location.locationID
                            WHERE placeID=?');

    $stmt->execute(array($place_id));

    return $stmt->fetchAll();
}


function get_house_address_city($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT city
                            FROM Place NATURAL JOIN LOCATION
                            WHERE placeID=?
                            
                            ');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_house_address_country($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT country
                            FROM Place NATURAL JOIN LOCATION
                            WHERE placeID=?
                            
                            ');

    $stmt->execute(array($place_id));

    return $stmt->fetch();
}

function get_user_location($user_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT city, country
                            FROM User NATURAL JOIN LOCATION
                            WHERE userID=?
                            
                            ');

    $stmt->execute(array($user_id));

    return $stmt->fetch();
}

function get_avg_price($place_id)
{

    $db = Database::instance()->db();

    $stmt = $db->prepare('SELECT Avg(Availability.pricePerDay) as avg
                            FROM Place Natural Join Availability  
                            Where Place.placeID=? 
                        ');
                        
    $stmt->execute(array($place_id));
    return $stmt->fetch();
}
