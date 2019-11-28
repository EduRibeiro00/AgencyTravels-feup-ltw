<?php
include_once('../includes/session_include.php');

    // verify if user is already logged in
    if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
        die(json_encode(array('message' => 'user not logged in')));
    }

    // TODO: passar a action edit profile do outro branch para ajax
?>