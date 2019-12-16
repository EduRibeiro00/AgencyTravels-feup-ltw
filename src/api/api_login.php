<?php
    include_once('../includes/session_include.php');
    include_once('../database/db_user.php');
    include_once('../includes/input_validation.php');

    if(!isset($_POST['username']) || !validateUsernameValue($_POST['username']) ||
       !isset($_POST['password']) || !validatePasswordValue($_POST['password'])) {
            $message = 'values not defined';
            echo json_encode(array('message' => $message));
            return;
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