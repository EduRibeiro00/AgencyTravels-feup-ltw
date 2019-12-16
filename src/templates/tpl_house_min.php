<?php function draw_house_miniature($place) { ?>
    <article>
        <a href="place_info.php?place_id=<?=$place['placeID']?>">
            <div class="place_info">
              <h4><?=htmlspecialchars($place['title'])?></h4>
              <p><?=number_format($place['avg_price'], 2);?>€/night</p>
            </div>
			<img src="../assets/images/places/medium/<?=$place['images'][0]['image']?>">
		</a>
    </article>
<?php } ?>


<?php function draw_place_listing($title, $places, $link) { ?>
    <section class="place-listing">
        <h3><?=htmlspecialchars($title)?></h3>
            
        <div class="places_list">
            <?php 
            if($places == null || count($places) == 0) { ?>
              <p><em>No places available</em></p>
           <?php }
            else {
              foreach($places as $place) {
                draw_house_miniature($place);
              }
            } ?>
        </div>
        
        <div class="more"> 
          <a class="button" href=<?=$link?>>More</a>
        </div>

    </section>
<?php } ?>