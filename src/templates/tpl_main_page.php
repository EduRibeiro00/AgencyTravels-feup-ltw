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
    <main>
    
      <?php
        draw_top_destinations();
        draw_trending();
        draw_city_places();
      ?>

	</main>
<?php } ?>


<?php function draw_top_destinations() { ?>
        <section id="topdests">
          <h3>Top Destinations</h3>  <!-- passar no href o indice de cada cidade -->
            <ol>
                <li>
                  <a href="city_places.php">
                    <img src="https://scontent.fopo1-1.fna.fbcdn.net/v/t1.0-9/53194190_10157166664404485_2869923037250060288_o.jpg?_nc_cat=107&_nc_oc=AQmd4OhTbJXllyC6ZqEZ8Hu3A4HYM60JhUWTaNCsVDEAzYsjdeR6IEBhSI-JCL8F-F0&_nc_ht=scontent.fopo1-1.fna&oh=72a53586015e23749a6d8e6d07cd3c40&oe=5E5BBA67">
                    <span>Porto</span>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://scontent.fopo1-1.fna.fbcdn.net/v/t31.0-8/22256773_10159433832965716_4440218232204276718_o.jpg?_nc_cat=110&_nc_oc=AQmWf288jQeZD4fisnfwZLMSZOwRbPV64OTU5MLoRdiuupfqK7LOanp9GDbzDluDGIU&_nc_ht=scontent.fopo1-1.fna&oh=ada0cf440fa0887ee7f48d19247f390b&oe=5E3FA6E3">
					<span>Lisboa</span>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="https://i0.wp.com/www.vortexmag.net/wp-content/uploads/2018/12/sesimbra5-e1543761363529.jpg?resize=640%2C427&ssl=1">
					<span>Sesimbra</span>
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
                    <img src="http://www.terranova.pt/sites/default/files/styles/node-detail/public/field/image/cira_regiao_1.jpg?itok=QHeaM12r">
					<span>Estarreja</span>
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
					<img src="https://scontent.fopo1-1.fna.fbcdn.net/v/t1.0-9/53194190_10157166664404485_2869923037250060288_o.jpg?_nc_cat=107&_nc_oc=AQmd4OhTbJXllyC6ZqEZ8Hu3A4HYM60JhUWTaNCsVDEAzYsjdeR6IEBhSI-JCL8F-F0&_nc_ht=scontent.fopo1-1.fna&oh=72a53586015e23749a6d8e6d07cd3c40&oe=5E5BBA67">
                    <span>Porto</span> 245 Reservations
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
				  	<img src="https://scontent.fopo1-1.fna.fbcdn.net/v/t31.0-8/22256773_10159433832965716_4440218232204276718_o.jpg?_nc_cat=110&_nc_oc=AQmWf288jQeZD4fisnfwZLMSZOwRbPV64OTU5MLoRdiuupfqK7LOanp9GDbzDluDGIU&_nc_ht=scontent.fopo1-1.fna&oh=ada0cf440fa0887ee7f48d19247f390b&oe=5E3FA6E3">
				    <span>Lisboa</span> 209 Reservations
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
				  	<img src="https://i0.wp.com/www.vortexmag.net/wp-content/uploads/2018/12/sesimbra5-e1543761363529.jpg?resize=640%2C427&ssl=1">
					<span>Sesimbra</span> 133 Reservations
                  </a>
                </li>
                <li>
                  <a href="city_places.php">
				  	<img src="http://www.terranova.pt/sites/default/files/styles/node-detail/public/field/image/cira_regiao_1.jpg?itok=QHeaM12r">
				  	<span>Estarreja</span> 82 Reservations
                  </a>
                </li>
            </ol>
        </section>
<?php } ?>


<?php function draw_city_places() { ?>
    <section id="cityplaces">
          <h3>Lisboa: some places</h3>  <!-- passar no href o indice de cada local -->
            
        <div id="places_list">
            <article>
              <a href="place_info.php">
                <h4>Casa Robles</h4>
                <p>35€/noite</p>
				<img src="https://scontent.fopo1-1.fna.fbcdn.net/v/t1.0-9/18010648_409832186056066_4504861782770486398_n.jpg?_nc_cat=104&_nc_oc=AQn2gvIH6cU1JOTsLn6-qTZfy09NUvfxa6AcLSIXxzOJfXXofsLzIXVfu2tg6pHTj7A&_nc_ht=scontent.fopo1-1.fna&oh=ae7682cfa003482698d804078676a3f4&oe=5E478A31">
			  </a>
            </article>
            
            <article>
              <a href="place_info.php">
                <h4>Parlamento Guest House</h4>
                <p>55€/noite</p>
				<img src="https://www.parlamento.pt/Parlamento/PublishingImages/Paginas/Imagens-apontamentos/AF00036_2009.jpg">
              </a>
            </article>

            <article>
              <a href="place_info.php">
                <h4>Space Bogota: Porta 18</h4>
                <p>70€/noite</p>
				<img src="https://ogimg.infoglobo.com.br/in/2820089-2c1-400/FT1086A/652/xA-Praca-de-Bolivar-que-reune-construcoes-historicas-em-BogotaFoto-Andre-Teixeira.jpg.pagespeed.ic.JaKdg6HPqh.jpg">
              </a>
            </article>
        </div>
            
        <a href="city_places.php">More</a>   <!-- passar no href o indice da cidade -->
        
        </section>
<?php } ?>