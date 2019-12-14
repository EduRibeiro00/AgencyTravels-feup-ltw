<?php

include_once('../database/db_user.php');
include_once('../database/db_places.php');

function initGoogleMaps($houseListing=true){?>
    
    <!-- Place Holder to hold the map -->
    <div id="map">

    </div>
    <?php
    if($houseListing==true){ ?>

        <script src='../js/googleMapsHouseList.js' defer></script>
    
    <?php 
    }else{

    ?>

        <script src='../js/googleMapsHouseForm.js' defer></script>

    <?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0_ruPYuzA1ntPaautYqVqtjOT96oNLSE&callback=initGoogleMapsServices" async defer></script>

<?php } ?>