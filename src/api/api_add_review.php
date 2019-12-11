<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../database/db_places.php');

    $reservationID = $_POST['reservationID'];
    $stars = $_POST['stars'];
    $comment = $_POST['comment'];
    $lastReviewID = $_POST['lastReviewID'];
    $placeID = $_POST['placeID'];

    if(isset($_SESSION['userID']) && checkIfUserHasReservation($_SESSION['userID'], $reservationID)) {

        if(!addReview($reservationID, $stars, $comment)) {
            $message = 'no';
            echo json_encode(array('message' => $message));
        }
        else {
            $newReviews = getHouseCommentsAfterID($placeID, $lastReviewID);
            $newRating = getPlaceNewRating($placeID);
            $message = 'yes';
            echo json_encode(array('message' => $message, 'reviews' => $newReviews, 'newRating' => $newRating));
        }
    }
    else {
        $message = 'no';
        echo json_encode(array('message' => $message));
    }
?>