<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');

    if(isset($_SESSION['userID'])) {
        die(json_encode(array('message' => 'user already logged in')));
    }

    $result = checkUserCredentials($username, $password);

    if($result === false) {
        echo json_encode(array('message' => 'invalid credentials'));
    }
    else {
        echo json_encode(array('message' => 'login successful'));
    }
?>