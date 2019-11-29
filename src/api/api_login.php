<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');

    if(!isset($_POST['username']) || $_POST['username'] == '' ||
       !isset($_POST['password']) || $_POST['password'] == '') {
            echo json_encode(array('message' => 'values not defined'));
       }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if(isset($_SESSION['userID'])) {
        $message = 'user already logged in';
    }
    else {
        $result = checkUserCredentials($username, $password);

        if($result === false) {
            $message = 'invalid credentials';
        }
        else {
            $_SESSION['userID'] = $result['userID'];
            $message = 'login successful';
        }
    }

    echo json_encode(array('message' => $message));
?>