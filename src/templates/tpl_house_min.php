<?php function draw_house_miniature($place) { ?>
    <article>
        <a href="place_info.php?place_id=<?=$place['placeID']?>">
            <div class="place_info">
              <h4><?=$place['title']?></h4>
              <p><?=$place['avg_price']?>€/noite</p>
            </div>
			<img src="../assets/images/places/medium/<?=$place['image']?>">
		</a>
    </article>
<?php } ?>


<?php function draw_place_listing($title, $places, $link) { ?>
    <section class="place-listing">
        <h3><?=$title?></h3>
            
        <div class="places_list">
            <?php foreach($places as $place) {
              draw_house_miniature($place);
            } ?>
        </div>
        
        <div class="more"> 
          <a class="button" href=<?=$link?>>More</a>
        </div>

    </section>
<?php } ?>