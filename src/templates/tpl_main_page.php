<?php

include_once('../templates/tpl_house_min.php');

function draw_mainpage_body($topdests, $trendingdests, $randcity, $randplaces) { ?>
  <main id="main-page">
      <?php
        draw_top_destinations($topdests);
        draw_trending($trendingdests);

        $title = htmlspecialchars($randcity['city']) . ': some places';
        $link = 'list_places.php?location=' . htmlspecialchars($randcity['country']) . '+-+' . htmlspecialchars($randcity['city']);
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
                    <a href="list_places.php?location=<?=$topdest['country']?>+-+<?=$topdest['city']?>">
                        <img class="circular-img" src="../assets/images/places/small/<?=$topdest['image']?>">
                      <span><?=htmlspecialchars($topdest['city'])?>, <?=htmlspecialchars($topdest['country'])?></span>
                    </a>
                  </li>
                <?php } ?>
            </ol>
        </section>
<?php } ?>


<?php function draw_trending($trendingdests) { ?>
    <section id="trending">
          <h3>Trending</h3>
            <ol>
                <?php foreach($trendingdests as $trendingdest) {?>
                  <li>
                    <a href=list_places.php?location=<?=htmlspecialchars($trendingdest['country'])?>+-+<?=htmlspecialchars($trendingdest['city'])?>>
                      <img class="circular-img" src="../assets/images/places/small/<?=$trendingdest['image']?>">
                    <span><?=htmlspecialchars($trendingdest['city'])?></span> ↑ <?=$trendingdest['numReservations']?> 
                    </a>
                  </li>
				<?php }?>
            </ol>
        </section>
<?php } ?>