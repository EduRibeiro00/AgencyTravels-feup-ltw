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
