<?php
include_once('../includes/session_include.php');
include_once('../includes/img_upload.php');
include_once('../database/db_user.php');

// verify if user is already logged in
  if(isset($_SESSION['userID'])) {
      $message = 'user already logged in';
  }
  else {
    $image = $_FILES['imageFile']['tmp_name'];
    $username = $_POST['username'];
    $name = $_POST['username'];
    $password = $_POST['password'];
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
      $returnValue = insertUserInfo($username, $name, $password, $email, $bio, $birthDate, $gender, $locationID);

      if($returnValue === true) {
          $id = getUserID($username);
          if($hasFile == "yes") {
            uploadUserImage($id, $image);
          }
          $_SESSION['userID'] = $id;
          $message = 'signup completed';  
      }
      else {
          $array = explode(" ", $returnValue);
          $message = $array[count($array) - 1];
      }
    }
  }
  
  echo json_encode(array('message' => $message));
?>