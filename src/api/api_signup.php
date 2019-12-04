<?php
include_once('../includes/session_include.php');
include_once('../includes/img_upload.php');
include_once('../database/db_user.php');

// verify if user is already logged in
  if(isset($_SESSION['userID'])) {
      $message = 'user already logged in';
  }
  else {
    $image = $_POST['image'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];

    /* TODO: imagem e location hardcoded por agora, meter a funcionar no tpl_profile_form
             E preciso guardar a imagem nos assets, ir buscar o path e fazer um update na
             base de dados, associando a imagem ao ID do user (preciso verificar se a imagem
             e a mesma e nao foi modificada, e preciso verificar se o user nao tinha imagem)
    */
    $locationID = 1;

    if (!checkIfImageIsValid($image)) {
      $message = 'invalid image';
    }
    else {
      $returnValue = insertUserInfo($username, $name, $password, $email, $bio, $birthDate, $gender, $locationID);

      if($returnValue === true) {
          $id = getUserID($username);
          if($image != "") {
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