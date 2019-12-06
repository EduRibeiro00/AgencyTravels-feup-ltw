<?php function draw_slideshow($slideshowimgs, $title = null, $titleLink = null) { ?>
    <section id="slideshow">

      <div class="carousel">

        <?php if($title != null && $titleLink != null) { ?>
          <h3><a href=<?=$titleLink?>><?=$title?></a></h3>
        <?php } ?>

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
                    <img src="../assets/images/places/big/<?=$slideshowimgs[$i - 1]['image']?>">
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
