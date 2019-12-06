<?php
    include_once('../database/db_connection.php');

    function getUserInformation($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                             FROM User NATURAL JOIN Location NATURAL JOIN Image
                             WHERE userID = ?'
                             );
        $stmt->execute(array($userID));
        return $stmt->fetch();
    }

    function getUserPlaces($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *, (SELECT image
                                         FROM Image
                                         WHERE placeID = Place.placeID) as image
                             FROM Place
                             WHERE ownerID = ?'
                            );
        $stmt->execute(array($userID));
        return $stmt->fetchAll();
    }

    function updateUserInfo($userID, $username, $name, $password, $email, $bio, $birthDate, $gender, $locationID) {
        $db = Database::instance()->db();
        try {
            $stmt = $db->prepare('UPDATE User
                                  SET name = ?,
                                      username = ?,
                                      password = ?,
                                      email = ?,
                                      description = ?,
                                      birthDate = ?,
                                      gender = ?,
                                      locationID = ?
                                   WHERE userID = ?' 
                                );
         $stmt->execute(array($name, $username, $password, $email, $bio, $birthDate, $gender, $locationID, $userID));
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }

        return true;
    }


    function insertUserInfo($username, $name, $password, $email, $bio, $birthDate, $gender, $locationID) {
        $db = Database::instance()->db();
        try {
            $stmt = $db->prepare('INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID)
                                      VALUES(?, ?, ?, ?, ?, ?, ?, ?)' 
                                );
            $stmt->execute(array($name, $username, $password, $email, $bio, $birthDate, $gender, $locationID));
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }

        return true;
    }


    function getUserID($username) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT userID
                              FROM User
                              WHERE username = ?
                            ');
         $stmt->execute(array($username));
         return $stmt->fetch()['userID'];
    }

    function insertImageForUser($userID, $image) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('INSERT INTO Image (userID, image)
                              VALUES(?, ?)'
                            );
        $stmt->execute(array($userID, $image));
    }


    function deleteImageForUser($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('DELETE
                              FROM Image
                              WHERE userID = ?'
                            );
        $stmt->execute(array($userID));
    }


    function getUserImage($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT image
                              FROM Image
                              WHERE userID = ?'
                            );
        $stmt->execute(array($userID));
        $result = $stmt->fetch();
        if($result === false) {
            return "noImage.png";
        }
        else {
            return $result['image'];
        }
    }


    function removeUserImage($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('DELETE
                              FROM Image
                              WHERE userID = ?'
                            );
        $stmt->execute(array($userID));
    }


    function checkUserCredentials($username, $password) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT userID
                              FROM User
                              WHERE username = ? AND password = ?'
                            );
        $stmt->execute(array($username, $password));
        return $stmt->fetch();
    }

    function checkPasswordThroughID($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT password
                              FROM User
                              WHERE userID = ?'
                            );
        $stmt->execute(array($userID));
        return $stmt->fetch();
    }
    
    function getUserNumberofReservations($userID){
        $db = Database::instance()->db();

        $stmt = $db->prepare('SELECT count(*) as cnt
                              FROM Reservation Natural Join Place Natural Join User
                              WHERE User.userID = ?
                            ');
         $stmt->execute(array($userID));
         return $stmt->fetch();
    }
?>