<?php
  include_once('../database/db_user.php');

  $id = $_POST['userID'];
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

  $returnValue = updateUserInfo($id, $username, $name, $password, $email, $bio, $birthDate, $gender, $locationID);

  if($returnValue === true) {
      // apenas para testar
      $resultImage = updateImageForUser($id, "http://www.risadas.pt/img/videos/06/cristiano_ronaldo_e_cristiano_reinaldo_o_melhor_jogador_do_m_img_1006.jpg");
      if($resultImage === false) {
        insertImageForUser($id, "http://www.risadas.pt/img/videos/06/cristiano_ronaldo_e_cristiano_reinaldo_o_melhor_jogador_do_m_img_1006.jpg");
      }
      
      header('Location: ../pages/profile_page.php?userID=' . $id);
  }
  else {
      print_r($returnValue);
      /* TODO: ver como utilizar return value da query para retornar valor para profile form,
               de modo a indicar p ex "username ja ocupado" (preciso ajax?)
      */
  }
  
?>