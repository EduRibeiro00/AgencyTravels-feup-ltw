<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../database/db_places.php');

    $reviewID = $_POST['reviewID'];
    $comment = $_POST['comment'];
    $lastReplyID = $_POST['lastReplyID'];

    if(isset($_SESSION['userID']) && $_SESSION['userID'] != "") {

        if(!addReply($reviewID, $comment, $_SESSION['userID'])) {
            $message = 'no';
            echo json_encode(array('message' => $message));
        }
        else {
            $newReplies = getRepliesForReviewAfterID($reviewID, $lastReplyID);
            $message = 'yes';
            echo json_encode(array('message' => $message, 'replies' => $newReplies));
        }
    }
    else {
        $message = 'no';
        echo json_encode(array('message' => $message));
    }
?>