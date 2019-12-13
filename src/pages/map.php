<?php

include_once('../templates/tpl_common.php');
include_once('../database/db_user.php');

if (isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
  $user_info = getUserInformation(2);
  $jsFiles = ['../js/main.js'];
} else {
  //die(header('Location: ../pages/initial_page.php'));
  $user_info = getUserInformation(2);
  $jsFiles = ['../js/main.js'];
}
draw_head($jsFiles);
draw_navbar($user_info, false);

initGoogleMaps();
draw_footer();

?>
