<?php
    include_once('../database/db_connection.php');
    include_once('../database/db_images.php');

    function getUserInformation($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                             FROM User NATURAL JOIN Location
                             WHERE userID = ?'
                             );
        $stmt->execute(array($userID));
        $userInfo = $stmt->fetch();
        if($userInfo === false) {
            return false;
        }
        $userInfo['image'] = getUserImage($userID);
        return $userInfo;
    }

    function getUserPlaces($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                             FROM Place
                             WHERE ownerID = ?'
                            );
        $stmt->execute(array($userID));
        $all_places = $stmt->fetchAll();
        for($i = 0; $i < count($all_places); $i++) {
            $all_places[$i]['images'] = getPlaceImages($all_places[$i]['placeID']);
        }
        return $all_places;
    }


    function getUserReservations($userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                              FROM Reservation NATURAL JOIN Place
                              WHERE touristID = ?
                              ORDER BY startDate ASC, endDate ASC'
                            );
        $stmt->execute(array($userID));
        $all_places = $stmt->fetchAll();
        for($i = 0; $i < count($all_places); $i++) {
            $all_places[$i]['images'] = getPlaceImages($all_places[$i]['placeID']);
            $all_places[$i]['reviewID'] = getReviewForReservation($all_places[$i]['reservationID']);
        }
        return $all_places;
    }

    
    function getReservationsForOwner($ownerID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                              FROM Reservation NATURAL JOIN Place JOIN User ON User.userID = Reservation.touristID JOIN Image ON User.userID = Image.userID
                              WHERE ownerID = ?
                              ORDER BY startDate ASC, endDate ASC'
                            );
        $stmt->execute(array($ownerID));
        $all_places = $stmt->fetchAll();
        for($i = 0; $i < count($all_places); $i++) {
            $all_places[$i]['placeImages'] = getPlaceImages($all_places[$i]['placeID']);
        }
        return $all_places;
    }


	function userHasReservationsInRange($userID, $checkin, $checkout) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT 1
                              FROM Reservation
                              WHERE touristID = ?
                              AND date(?) < date(endDate) AND date(?) > date(startDate)'
                            );
        $stmt->execute(array($userID, $checkin, $checkout));
        return $stmt->fetchAll();
	}
	

    function getUserReservationsForPlace($userID, $placeID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                              FROM Reservation
                              WHERE touristID = ? AND placeID = ?'
                            );
        $stmt->execute(array($userID, $placeID));
        return $stmt->fetchAll();
    }


    function getReviewForReservation($reservationID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT reviewID
                              FROM Review
                              WHERE reservationID = ?'
                            );
        $stmt->execute(array($reservationID));
        $review = $stmt->fetch();
        if($review === false) {
            return false;
        }
        else {
            return $review['reviewID'];
        }
    }


    function cancelUserReservation($reservationID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('DELETE
                              FROM Reservation
                              WHERE reservationID = ?'
                            );
        return $stmt->execute(array($reservationID));
    }


    function checkIfUserCanCancelReservation($userID, $reservationID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                              FROM Reservation
                              WHERE reservationID = ? AND touristID = ?'
                            );
        $stmt->execute(array($reservationID, $userID));
        if($stmt->fetch() !== false) {
            return true;
        }
        
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT *
                              FROM Reservation NATURAL JOIN Place
                              WHERE reservationID = ? AND ownerID = ?'
                            );
        $stmt->execute(array($reservationID, $userID));
        if($stmt->fetch() !== false) {
            return true;
        }
        else {
            return false;
        }
    }


    function getReviewsForUserPlaces($userID, $limit) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT comment, Review.stars as stars, User.username as username, User.userID as userID, image, Review.date as date, Place.placeID as placeID, Review.reviewID as reviewID
                              FROM Place NATURAL JOIN Reservation NATURAL JOIN Review JOIN User on Reservation.touristID=User.userID JOIN Image on User.userID = Image.userID
                              WHERE Place.ownerID = ?
                              LIMIT ?');
        $stmt->execute(array($userID, $limit));
        return $stmt->fetchAll();
    }


    function updateUserInfo($userID, $username, $name, $password, $email, $bio, $birthDate, $gender, $locationID) {
        $options = ['cost' => 12];
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
         $stmt->execute(array($name, $username, password_hash($password, PASSWORD_DEFAULT, $options), $email, $bio, $birthDate, $gender, $locationID, $userID));
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }

        return true;
    }


    function insertUserInfo($username, $name, $password, $email, $bio, $birthDate, $gender, $locationID) {
        $options = ['cost' => 12];
        $db = Database::instance()->db();
        try {
            $stmt = $db->prepare('INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID)
                                      VALUES(?, ?, ?, ?, ?, ?, ?, ?)' 
                                );
            $stmt->execute(array($name, $username, password_hash($password, PASSWORD_DEFAULT, $options), $email, $bio, $birthDate, $gender, $locationID));
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


    function checkUserCredentials($username, $password) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT userID, password
                              FROM User
                              WHERE username = ?'
                            );
        $stmt->execute(array($username));
        $info = $stmt->fetch();
        if(!($info !== false && password_verify($password, $info['password']))) {
            return false;
        }
        else {
            return $info;
        }
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
    
    function getUserPlacesNumberofReservations($userID){
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT count(*) as cnt
                              FROM Reservation Natural Join Place, User
                              WHERE ownerID = User.userID AND User.userID = ?
                            ');
         $stmt->execute(array($userID));
         return $stmt->fetch()['cnt'];
    }


    function addReview($reservationID, $stars, $comment) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('INSERT INTO Review (comment, date, stars, reservationID) VALUES (?, ?, ?, ?)');
        try {
            $stmt->execute(array($comment, date('Y-m-d'), $stars, $reservationID));
        }
        catch(PDOException $e) {
            return false;
        }
        return true;
    }

    function addReply($reviewID, $comment, $userID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('INSERT INTO Reply (comment, date, reviewID, userID) VALUES (?, ?, ?, ?)');
        try {
            $stmt->execute(array($comment, date('Y-m-d'), $reviewID, $userID));
        }
        catch(PDOException $e) {
            return false;
        }
        return true;
    }


    function getReservationInfo($reservationID) {
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT * FROM Reservation WHERE reservationID = ?');
        $stmt->execute(array($reservationID));
        return $stmt->fetch();
    }
?>