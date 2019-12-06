<?php

include_once('../templates/tpl_house_min.php');

function draw_mainpage_body($topdests, $trendingdests, $randcity, $randplaces) { ?>
  <main id="main-page">
      <?php
        draw_top_destinations($topdests);
        draw_trending($trendingdests);

        $title = $randcity['city'] . ': some places';
        $link = 'city_places.php?location_id=' . $randcity['locationID'];
        draw_place_listing($title, $randplaces, $link);
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
                        <img class="circular-img" src="../assets/images/places/small/<?=$topdest['image']?>">
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
                      <img class="circular-img" src="../assets/images/places/small/<?=$trendingdest['image']?>">
                    <span><?=$trendingdest['city']?></span> <?=$trendingdest['numReservations']?> Reservations
                    </a>
                  </li>
                <?php ?>
            </ol>
        </section>
<?php } ?>