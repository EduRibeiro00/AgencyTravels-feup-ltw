<?php
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_main_page.php');
  include_once('../database/db_places.php');

  $randcity = getRandomCity();
  $randplaces = getRandomPlacesFromCity($randcity['locationID'], 3);

  draw_head();
  draw_navbar();
  //draw_slideshow();
  draw_mainpage_body($randcity, $randplaces);
  draw_footer();
?>