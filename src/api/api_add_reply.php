<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../database/db_places.php');
    include_once('../includes/input_validation.php');

    $reviewID = $_POST['reviewID'];
    $comment = $_POST['comment'];
    $lastReplyID = $_POST['lastReplyID'];

    if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {

        if(!(validatePosIntValue($reviewID) && validateAnyIntValue($lastReplyID) && addReply($reviewID, $comment, $_SESSION['userID']))) {
            $message = 'error';
            echo json_encode(array('message' => $message));
        }
        else {
            $newReplies = getRepliesForReviewAfterID($reviewID, $lastReplyID);
            $message = 'yes';
            echo json_encode(array('message' => $message, 'replies' => $newReplies));
        }
    }
    else {
        $message = 'not logged in';
        echo json_encode(array('message' => $message));
    }
?>