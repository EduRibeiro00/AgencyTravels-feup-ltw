<?php
include_once('../database/db_connection.php');


function getPlaceImages($placeID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT image
                          FROM Image
                          WHERE placeID = ?');
    $stmt->execute(array($placeID));
    return $stmt->fetchAll();
}

function getUserImage($userID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare(
        'SELECT image
                          FROM Image
                          WHERE userID = ?'
    );
    $stmt->execute(array($userID));
    $result = $stmt->fetch();
    if ($result === false) {
        return "noImage.png";
    } else {
        return $result['image'];
    }
}

function insertImageForUser($userID, $image) {
    $db = Database::instance()->db();
    $stmt = $db->prepare(
        'INSERT INTO Image (userID, image)
                          VALUES(?, ?)'
    );
    $stmt->execute(array($userID, $image));
}


function deleteImageForUser($userID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare(
        'DELETE
                          FROM Image
                          WHERE userID = ?'
    );
    $stmt->execute(array($userID));
}


function removeUserImage($userID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare(
        'DELETE
                          FROM Image
                          WHERE userID = ?'
    );
    $stmt->execute(array($userID));
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
    //TODO: TRY CATCH ?
    $db = Database::instance()->db();
    $stmt = $db->prepare(
        'INSERT INTO Image (placeID, image)
                          VALUES(?, ?)'
    );
    $stmt->execute(array($placeID, $image));
}

function removeImageFromPlace($placeID, $image) {
    try {
        $db = Database::instance()->db();
        $stmt = $db->prepare(
            'DELETE FROM Image
            WHERE image LIKE ? AND placeID=?'
        );
        $stmt->execute(array($image,$placeID));

     } catch (PDOException $e) {
        return $e->getMessage();
    }
    return true;
}

function getNumberOfImagesForPlace($placeID) {
    $db = Database::instance()->db();
    $stmt = $db->prepare('SELECT count(*) as nImages
                        FROM Image
                          WHERE placeID=?'
    );
    $stmt->execute(array($placeID));
    return $stmt->fetch();
}
