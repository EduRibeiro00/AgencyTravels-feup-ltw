<?php function draw_slideshow() { ?>
    <section id="slideshow">
        <input checked type=radio name="slider" id="slide1" />
        <input type=radio name="slider" id="slide2" />
        <input type=radio name="slider" id="slide3" />
        <input type=radio name="slider" id="slide4" />
        <input type=radio name="slider" id="slide5" />

        <h3><a ref="city_places.php">Places in Porto</a></h3> <!-- passar no href o indice da cidade -->

        <section class="slider-wrapper">
            <section class="inner">
              <article>
                <img src="https://farm9.staticflickr.com/8059/28286750501_dcc27b1332_h_d.jpg" />
              </article>

              <article>
                <img src="https://farm6.staticflickr.com/5812/23394215774_b76cd33a87_h_d.jpg" />
              </article>

              <article>
                <img src="https://farm8.staticflickr.com/7455/27879053992_ef3f41c4a0_h_d.jpg" />
              </article>

              <article>
                <img src="https://farm8.staticflickr.com/7367/27980898905_72d106e501_h_d.jpg" />
              </article>

              <article>
                <img src="https://farm8.staticflickr.com/7356/27980899895_9b6c394fec_h_d.jpg" />
              </article>
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


<?php function draw_mainpage_body() { ?>
    <section id="mainpagebody">
    
      <?php
        draw_top_destinations();
        draw_trending();
        draw_city_places();
      ?>

    </section>
<?php } ?>


<?php function draw_top_destinations() { ?>
        <section id="topdests">
            <h3>Top Destinations</h3>  <!-- passar no href o indice de cada cidade -->
            <ol>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Porto</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Lisboa</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Sesimbra</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Estarreja</p>
                  </a>
                </li>
            </ol>
        </section>
<?php } ?>


<?php function draw_trending() { ?>
    <section id="trending">
            <h3>Trending</h3>   <!-- passar no href o indice de cada cidade -->
            <ol>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Porto</p>
                    <p>245 Reservations</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Lisboa</p>
                    <p>209 Reservations</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Sesimbra</p>
                    <p>133 Reservations</p>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
                    <p>Estarreja</p>
                    <p>82 Reservations</p>
                  </a>
                </li>
            </ol>
        </section>
<?php } ?>


<?php function draw_city_places() { ?>
    <section id="cityplaces">
            <h3>Lisboa: some places</h3>  <!-- passar no href o indice de cada local -->
            
            <article>
              <a href="place_info.php">
                <h4>Casa Robles</h4>
                <p>35€/noite</p>
                <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
              </a>
            </article>
            
            <article>
              <a href="place_info.php">
                <h4>Parlamento Guest House</h4>
                <p>55€/noite</p>
                <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
              </a>
            </article>

            <article>
              <a href="place_info.php">
                <h4>Space Bogota: Porta 18</h4>
                <p>70€/noite</p>
                <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.facebook.com%2FFCPorto%2Fphotos%2F-brasil-panam%25C3%25A1-est%25C3%25A1dio-do-drag%25C3%25A3o-23-de-mar%25C3%25A7o-s%25C3%25A1bado-17h-gmt-venda-online-httpbit%2F10157166664394485%2F&psig=AOvVaw1pxbOnSQsnAb9rkuA4NcjB&ust=1573166429504000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDYyePT1uUCFQAAAAAdAAAAABAN">
              </a>
            </article>

            <a href="city_places.php">More</a>   <!-- passar no href o indice da cidade -->

        </section>
    </section>
<?php } ?>