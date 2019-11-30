<?php
  include_once('../includes/session_include.php');
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_main_page.php');
  include_once('../templates/tpl_slideshow.php');
  include_once('../database/db_places.php');
  include_once('../database/db_user.php');

  // slideshow
  $slideshowcity = getRandomCity();
  $slideshowimgs = getRandomImagesFromCity($slideshowcity['locationID'], 3);

  // top destinations
  $topdests = getTopDestinations();
 
  // trending destinations
  $trendingdests = getTrendingDestinations();

  // random city places
  $randcity = getRandomCity();
  $randplaces = getRandomPlacesFromCity($randcity['locationID'], 3);
  foreach($randplaces as $k => $place) {
    $randplaces[$k]['avg_price'] = getAveragePrice($place['placeID'])['avg_price'];
  }

  if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js'];
  }
  else {
    $user_info = NULL;
    $jsFiles = ['../js/main.js', '../js/login.js'];
  }


  draw_head($jsFiles);
  draw_navbar($user_info);
  draw_slideshow($slideshowcity, $slideshowimgs);

  draw_mainpage_body($topdests, $trendingdests, $randcity, $randplaces);
  draw_footer();
?> 