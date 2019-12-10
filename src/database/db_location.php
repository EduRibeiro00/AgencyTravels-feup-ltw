<?php
include_once('../database/db_connection.php');

function locationInsert($city,$country){

    try {
    
        $db = Database::instance()->db();
        $stmt = $db->prepare('INSERT INTO LOCATION(country,city)
                            VALUES(?,?)
                            ');
        $stmt->execute(array($country, $city));
    }
    
    catch (PDOException $e) {
        return $e->getMessage();
    }

    return true;
}

function locationGetID($city,$country){
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT locationID
                        FROM Location
                        WHERE city LIKE ? AND country LIKE ?
                        ');
    $stmt->execute(array($city,$country));
    
    return $stmt->fetch();
}



?>