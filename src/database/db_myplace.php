<?php
    include_once('../database/db_connection.php');


    function get_house_title($place_id){
        
        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT title
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();
    }

    function get_house_rating($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT rating
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();
    }

    function get_house_description($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT description
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

    function get_house_numRooms($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT numRooms
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }


    function get_house_capacity($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT capacity
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

    function get_house_numBathrooms($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT numBathrooms
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

    function get_house_address($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT address
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

    function get_house_gpsCoords($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT gpsCoords
                            FROM Place
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

     function get_house_comments($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT comment,Review.stars as stars, User.name as name
                            FROM Place NATURAL JOIN Reservation NATURAL JOIN Review JOIN User on Reservation.touristID=User.userID
                            WHERE placeID=?');

        $stmt->execute(array($place_id));

        return $stmt->fetchAll();

    }


    function get_house_address_city($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT city
                            FROM Place NATURAL JOIN LOCATION
                            WHERE placeID=?
                            
                            ');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }

    function get_house_address_country($place_id){

        $db = Database::instance()->db();
        
        $stmt=$db->prepare('SELECT country
                            FROM Place NATURAL JOIN LOCATION
                            WHERE placeID=?
                            
                            ');

        $stmt->execute(array($place_id));

        return $stmt->fetch();

    }



?>