<?php function draw_slideshow($slideshowcity, $slideshowimgs) { ?>
    <section id="slideshow">
        <input checked type=radio name="slider" id="slide1" />
        <input type=radio name="slider" id="slide2" />
        <input type=radio name="slider" id="slide3" />
        <input type=radio name="slider" id="slide4" />
        <input type=radio name="slider" id="slide5" />

        <h3><a ref="city_places.php?location_id=<?=$slideshowcity['locationID']?>">Places in <?=$slideshowcity['city']?></a></h3>

        <section class="slider-wrapper">
            <section class="inner">
              <?php foreach($slideshowimgs as $slideshowimg) { ?>
                <article>
                  <img src=<?=$slideshowimg['image']?>>
                </article>
              <?php } ?>
            </section>
        </section>

        <div class="slider-prev-next-control">
            <label for=slide1></label>
            <label for=slide2></label>
            <label for=slide3></label>
            <label for=slide4></label>
            <label for=slide5></label>
        </div>
        
        <div class="slider-dot-control">
          <label for=slide1></label>
          <label for=slide2></label>
          <label for=slide3></label>
          <label for=slide4></label>
          <label for=slide5></label>
        </div>
    </section>
<?php } ?>


<?php function draw_mainpage_body($topdests, $trendingdests, $randcity, $randplaces) { ?>
    <main>
    
      <?php
        draw_top_destinations($topdests);
        draw_trending($trendingdests);
        draw_randlocation_places($randcity, $randplaces);
      ?>

	</main>
<?php } ?>


<?php function draw_top_destinations($topdests) { ?>
        <section id="topdests">
          <h3>Top Destinations</h3>
            <ol>
                <?php foreach($topdests as $topdest) { ?>
                  <li>
                    <a href="city_places.php?location_id=<?=$topdest['locationID']?>">
                      <img src=<?=$topdest['image']?>>
                      <span><?=$topdest['city']?>, <?=$topdest['country']?></span>
                    </a>
                  </li>
                <?php } ?>
            </ol>
        </section>
<?php } ?>


<?php function draw_trending($trendingdests) { ?>
    <section id="trending">
          <h3>Trending</h3>   <!-- passar no href o indice de cada cidade -->
            <ol>
                <?php foreach($trendingdests as $trendingdest) ?>
                  <li>
                    <a href="city_places.php?location_id=<?=$trendingdest['locationID']?>">
					          <img src=<?=$trendingdest['image']?>>
                    <span><?=$trendingdest['city']?></span> <?=$trendingdest['numReservations']?> Reservations
                    </a>
                  </li>
                <?php ?>
            </ol>
        </section>
<?php } ?>


<?php function draw_randlocation_places($randcity, $randplaces) { ?>
    <section id="randlocationplaces">
          <h3><?=$randcity['city']?>: some places</h3>  <!-- passar no href o indice de cada local -->
            
        <div id="places_list">

            <?php foreach($randplaces as $place) { ?>
              <article>
              <a href="place_info.php?place_id=<?=$place['placeID']?>">
                <div class="place_info">
                  <h4><?=$place['title']?></h4>
                  <p><?=$place['avg_price']?>€/noite</p>
                </div>
				        <img src=<?=$place['image']?>>
			        </a>
            </article>  
            <?php } ?>

        </div>
        
        <div id="more"> 
          <a id="more_button" href="city_places.php?location_id=<?=$randcity['locationID']?>">More</a>
        </div>

        </section>
<?php } ?>