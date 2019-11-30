<?php
  include_once('../includes/session_include.php');
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_initial_page.php');
  include_once('../database/db_user.php');

  if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js'];
  }
  else {
    $user_info = NULL;
    $jsFiles = ['../js/main.js', '../js/login.js'];
  }

  draw_head($jsFiles, 'initial');
  draw_navbar($user_info, 'transparent');
  draw_initial_page();
  draw_footer();
?>