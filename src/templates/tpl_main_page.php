<?php function draw_slideshow($slideshowcity, $slideshowimgs) { ?>
    <section id="slideshow">
        <h3><a href="city_places.php?location_id=<?=$slideshowcity['locationID']?>">Places in <?=$slideshowcity['city']?></a></h3>

      <div class="carousel">
        <ul class="slides">

        <?php
          // max number of images in the slideshow is 6
          for($i = 1; (($i <= count($slideshowimgs)) && ($i <= 6)); $i++) {
            $next_image = ($i == count($slideshowimgs) || $i == 6) ? 1 : ($i + 1);
            $prev_image = ($i == 1) ? count($slideshowimgs) : ($i - 1);
          ?>

            <input type="radio" name="radio-buttons" id="img-<?=$i?>" checked />
            <li class="slide-container">
                <div class="slide-image">
                    <img src=<?=$slideshowimgs[$i - 1]['image']?>>
                </div>
                <div class="carousel-controls">
                    <label for="img-<?=$prev_image?>" class="prev-slide">
                        <span>&lsaquo;</span>
                    </label>
                    <label for="img-<?=$next_image?>" class="next-slide">
                      <span>&rsaquo;</span>
                    </label>
                </div>
            </li>
        <?php } ?>
        
        <div class="carousel-dots">
          <?php for($i = 1; (($i <= count($slideshowimgs)) && ($i <= 6)); $i++) { ?>
            <label for="img-<?=$i?>" class="carousel-dot" id="img-dot-<?=$i?>"></label>
          <?php } ?>
        </div>
        </ul>
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
        	<a class="more_button" href="city_places.php?location_id=<?=$randcity['locationID']?>">More <i class="fas fa-long-arrow-alt-down"></i></a>
        </div>

        </section>
<?php } ?>