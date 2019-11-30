<?php
    include_once('../database/db_connection.php');

    function getUserInformation($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                             FROM User NATURAL JOIN Image NATURAL JOIN Location
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


    // TODO: n sei se vai ser usado
    // function updateImageForUser($userID, $path) {
    //     $db = Database::instance()->db();
    //     try {
    //         $stmt = $db->prepare('UPDATE Image
    //                               SET image = ?
    //                               WHERE userID = ?'
    //                             );
    //         $stmt->execute(array($path, $userID));
    //     }
    //     catch (PDOException $e) { // error ocurred, user doesn't have an image
    //         return false;
    //     }
    //     return true;
    // }

    function insertImageForUser($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('INSERT INTO Image (userID)
                              VALUES(?)'
                            );
        $stmt->execute(array($userID));
    }

    function getUserImageMaxID() {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT imageID as id
                              FROM Image
                              WHERE userID IS NOT NULL
                              GROUP BY imageID
                              HAVING max(imageID) = id'
                            );
        $stmt->execute();
        return $stmt->fetch();
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
?>