<?php 
include_once('../templates/tpl_common.php');
include_once('../database/db_places.php');

function draw_horizontal_card($place, $drawingOption, $userID) { ?>
	<article class="row card">
		<!-- TODO: mudar para carroussel (para ja apenas a utilizar a 1a imagem, mas $place ja as tem todas) -->
		<a class="row" href="../pages/place_info.php?place_id=<?=$place['placeID']?>">
			<img class="hcard-img" src="../assets/images/places/medium/<?=$place['images'][0]['image']?>">
			<div class="column info">
			<h4><?=$place['title']?></h4>
			<!-- TODO: meter flexbox row, com divs ou spans -->
			<p><span class="card-guests"><?=$place['capacity']?> guests</span><span class="card-bedroom"><?=$place['numRooms']?> bedroom</span><span class="card-bathroom"><?=$place['numBathrooms']?> bathroom</span></p>
			<footer class="row">
				<?php if(isset($place['price']) && $place['price'] != null) { ?>
					<p><?=$place['price']?>€ / noite</p>
				<?php }
				else { ?>
					<p><em>No prices available</em></p>
				<?php } ?>
				<div class="card-rating">
					<?php if(isset($place['nVotes']) && $place['nVotes'] != null && $place['nVotes'] != 0) { 
						draw_star_rating($place['rating']); ?>
						<strong>(<?=$place['nVotes']?>)</strong>
					<?php }
					else { ?>
						<em>No rating available</em>
					<?php } ?>
				</div>
			</footer>
			</div>
		</a>
		<?php


		if($drawingOption == 'My_Houses' && isset($_SESSION['userID']) && $_SESSION['userID'] == $userID){ ?>
			<div class="column card-options">
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Edit
				</a>
			
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Statistics
				</a>
				
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Reservations
				</a>
			</div>
		<?php } 
		 else if($drawingOption == "My_Reserves") {
			// TODO: drawing options for My Reservations page
		 } ?>

	</article>
		
<?php } ?>