<?php

include_once('../database/db_user.php');
include_once('../database/db_places.php');

$jsFiles = ['../js/main.js', ];


function initGoogleMaps(){?>
    
    <!-- Place Holder to hold the map -->
    <div id="map">

    </div>
    <script src='googleMaps.js' defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE&callback=initMap" async defer></script>

<?php } ?>