<?php
include_once('../includes/session_include.php');
include_once('../includes/img_upload.php');
include_once('../database/db_user.php');

    // verify if user is already logged in
    if(!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
        $message = 'user not logged in';
    }
    else {
        $id = $_POST['userID'];
        $image = $_FILES['imageFile']['tmp_name'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $newPassword = $_POST['newPassword'];
        $confNewPassord = $_POST['confNewPassword'];
        $email = $_POST['email'];
        $bio = $_POST['bio'];
        $birthDate = $_POST['birthDate'];
        $gender = $_POST['gender'];
        $locationID = $_POST['location'];
        $hasFile = $_POST['hasFile'];

        if (!checkIfImageIsValid($image)) {
            $message = 'invalid image';
        }
        else {
            if($password != checkPasswordThroughID($id)['password']) {
                $message = 'password not valid';
            }
            else {
                if($newPassword != $confNewPassord) {
                    $message = 'new passwords dont match';
                }
                else {
                    if($newPassword == "") {
                        $passToBeInserted = $password;
                    }
                    else {
                        $passToBeInserted = $newPassword;
                    }

                    $returnValue = updateUserInfo($id, $username, $name, $passToBeInserted, $email, $bio, $birthDate, $gender, $locationID);

                    if($returnValue === true) {
                        if($hasFile == "yes") { // has an image
                            if($image != "") { // has a new image
                                updateUserImage($id, $image);
                            }
                        }
                        else {
                            deleteUserImage($id);
                        }
                        $message = 'profile edit completed';
                    }
                    else {
                        $array = explode(" ", $returnValue);
                        $message = $array[count($array) - 1];
                    }
                }
            }
        }
    }

    echo json_encode(array('message' => $message));
?>