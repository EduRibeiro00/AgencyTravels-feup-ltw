<?php
  include_once('../includes/session_include.php');
  include_once('../includes/input_validation.php');
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_initial_page.php');
  include_once('../database/db_user.php');

  if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js', '../js/filter.js'];
  }
  else {
    $user_info = NULL;
    $jsFiles = ['../js/main.js', '../js/filter.js', '../js/login.js'];
  }

  draw_head($jsFiles, 'initial');
  draw_navbar($user_info, true, 'transparent');
  draw_initial_page();
  draw_footer();
?>