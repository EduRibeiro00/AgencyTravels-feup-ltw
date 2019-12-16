<?php
include_once('../includes/session_include.php');
include_once('../includes/img_upload.php');
include_once('../database/db_user.php');
include_once('../includes/input_validation.php');

// verify if user is already logged in
if(!(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false)) {
    $message = 'user not logged in';
} else {
    $id = $_POST['userID'];
    $image = $_FILES['imageFile']['tmp_name'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $newPassword = $_POST['new-password'];
    $confNewPassord = $_POST['conf-new-password'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $locationID = $_POST['location'];
    $hasFile = $_POST['hasFile'];


    if (!validatePosIntValue($id)) {
        $message = 'userID not valid';
        echo json_encode(array('message' => $message));
        return;
    }
    if ($id != $_SESSION['userID']) { 
        $message = 'userID not match Session';
        echo json_encode(array('message' => $message));
        return;
    }
    if (!validateTextValue($name)) {
        $message = 'name not valid';
        echo json_encode(array('message' => $message));
        return;
    }
    if (!validateUsernameValue($username)) {
        $message = 'username not valid';
        echo json_encode(array('message' => $message));
        return;
    }

    if (!validateEmailValue($email)) {
        $message = 'email not valid';
        echo json_encode(array('message' => $message));
        return;
    }
    if (is_numeric($bio)) {
        $message = 'bio not valid';
        echo json_encode(array('message' => $message));
        return;
    }
    if (!validateDateValue($birthDate)) {
        $message = 'birthDate not valid';
        echo json_encode(array('message' => $message));
        return;
    }

    if (!validatePosIntValue($locationID)) {
        $message = 'locationID not valid';
        echo json_encode(array('message' => $message));
        return;
    }
    //GENDER CANT BE OTHER THING THAN M/F/O
    if ($gender != 'M' && $gender != 'F' && $gender != 'O') {
        $message = 'gender not valid';
        echo json_encode(array('message' => $message));
        return;
    }

    if (!checkIfImageIsValid($image)) {
        $message = 'invalid image';
    } else {
        //TODO:IMPLEMENT PASSWORD HASHING METHOD
        if (!password_verify($password, checkPasswordThroughID($id)['password'])) {
            $message = 'password not valid';
        } else {
            if ($newPassword != $confNewPassord) {
                $message = 'new passwords dont match';
            } else {
                if ($newPassword == "") {
                    $passToBeInserted = $password;
                } else {
                    $passToBeInserted = $newPassword;
                }

                $returnValue = updateUserInfo($id, $username, $name, $passToBeInserted, $email, $bio, $birthDate, $gender, $locationID);

                if ($returnValue === true) {
                    if ($hasFile == "yes") { // has an image
                        if ($image != "") { // has a new image
                            updateUserImage($id, $image);
                        }
                    } else {
                        deleteUserImage($id);
                    }
                    $message = 'profile edit completed';
                } else {
                    $array = explode(" ", $returnValue);
                    $message = $array[count($array) - 1];
                }
            }
        }
    }
}
echo json_encode(array('message' => $message));
