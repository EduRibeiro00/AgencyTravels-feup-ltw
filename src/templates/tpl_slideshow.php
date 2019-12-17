<?php function draw_main_slideshow($slideshowimgs, $title = null, $titleLink = null) { ?>
    <section id="slideshow">
		<div class="splide__track">
			<?php if($title != null && $titleLink != null) { ?>
				<h3><a href=<?=$titleLink?>><?=$title?></a></h3>
			<?php } ?>

			<ul class="splide__list">
				<?php for($i = 0; $i < count($slideshowimgs); $i++) { ?>
					<li class="splide__slide">
						<div class="splide__slide__container">
							<img src="../assets/images/places/big/<?=$slideshowimgs[$i]['image']?>">
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
<?php } 

function draw_hcard_slideshow($slideshowimgs, $placeID) { ?>
    <section class="hcard_slideshow">
		<div class="splide__track">
			<ul class="splide__list">
				<?php for($i = 0; $i < count($slideshowimgs); $i++) { ?>
					<li class="splide__slide">
						<div class="splide__slide__container">
						<a href="../pages/place_info.php?place_id=<?=$placeID?>">
							<img class="hcard-img" src="../assets/images/places/medium/<?=$slideshowimgs[$i]['image']?>">
						</a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
<?php } 


function draw_places_slideshow($places) { ?>
    <section class="minplaces_slideshow">
		<div class="splide__track">
			<ul class="splide__list">
				<?php for($i = 0; $i < count($places); $i++) { ?>
					<li class="splide__slide">
						<article class="splide__slide__container">
							<a href="place_info.php?place_id=<?=$places[$i]['placeID']?>">
								<div class="place_info">
								<h4><?=htmlspecialchars($places[$i]['title'])?></h4>
								<p><?=number_format($places[$i]['avg_price'], 2);?>€/night</p>
								</div>
								<img src="../assets/images/places/medium/<?=$places[$i]['images'][0]['image']?>">
							</a>
						</article>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
<?php } ?>