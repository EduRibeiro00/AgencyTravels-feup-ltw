<?php 
include_once('../templates/tpl_slideshow.php');

function draw_place_listing($title, $places, $link) { ?>
    <section class="place-listing">
        <h3><?=htmlspecialchars($title)?></h3>
            
        <div class="places_list">
            <?php 
            if($places == null || count($places) == 0) { ?>
              <p><em>No places available</em></p>
           <?php }
			else
				draw_places_slideshow($places);
             ?>
        </div>
    </section>
<?php } ?>