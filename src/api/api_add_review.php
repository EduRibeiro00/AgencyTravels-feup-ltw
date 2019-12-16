<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../database/db_places.php');
    include_once('../includes/input_validation.php');
    include_once('../includes/reservation_utils.php');

    $reservationID = $_POST['reservationID'];
    $stars = $_POST['stars'];
    $comment = $_POST['comment'];
    $lastReviewID = $_POST['lastReviewID'];
    $placeID = $_POST['placeID'];

    if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {

        if(!(validatePosIntValue($reservationID) && validatePosIntValue($placeID) && canUserReviewPlace($_SESSION['userID'], $placeID) !== false)) {
            $message = 'cant review';
            echo json_encode(array('message' => $message));
            return;
        }

        if(!(validateAnyIntValue($lastReviewID) && validatePosIntValue($stars) && $stars >= 1 && $stars <= 5 && addReview($reservationID, $stars, $comment))) {
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
        $message = 'not logged in';
        echo json_encode(array('message' => $message));
    }
?>