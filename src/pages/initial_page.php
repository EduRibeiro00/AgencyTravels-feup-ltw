<?php
  include_once('../includes/session_include.php');
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_initial_page.php');

  draw_head(['../js/main.js'], 'initial');
  draw_navbar('transparent');
  draw_initial_page();
  draw_footer();
?>