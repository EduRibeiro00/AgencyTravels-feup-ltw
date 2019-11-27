<?php
include_once('../includes/session_include.php');

    // verify if user is already logged in
    if(isset($_SESSION['userID'])) {
        die(json_encode(array('message' => 'user already logged in')));
    }

    // TODO: passar a action signup do outro branch para ajax
    
?>