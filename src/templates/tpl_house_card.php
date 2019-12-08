<!-- TODO: futuramente passar todos os house cards para aqui -->
<!-- TODO: passar para parametro em vez de estático -->
<?php 
include_once('../templates/tpl_common.php');
include_once('../database/db_places.php');

// TODO: depois por outros parametros
function draw_horizontal_card($place, $edit_features_active = false,$nVotes=false) { ?>
	<article class="row card">
		<!-- TODO: mudar para carroussel -->
		<a class="row" href="../pages/place_info.php?place_id=<?=$place['placeID']?>">
			<img class="hcard-img" src="../assets/images/places/medium/<?=$place['image']?>">
			<div class="column info">
			<h4><?=$place['title']?></h4>
			<!-- TODO: meter flexbox row, com divs ou spans -->
			<p><span class="card-guests"><?=$place['capacity']?> guests</span><span class="card-bedroom"><?=$place['numRooms']?> bedroom</span><span class="card-bathroom"><?=$place['numBathrooms']?> bathroom</span></p>
			<footer class="row">
				<!--TODO: ISTO NAO PODE SER ASSIM -->
				<p><?=$place['price']?>€/noite</p>
				<div class="card-rating">
					<?php draw_star_rating($place['rating']);?>
					
					<!--TODO: ISTO NAO PODE SER ASSIM; ACHO MAS EU N QUERO ERROS- RUBEN. QUE QUERY USRA PARA TIRAR PLACES + N VOTES -->
					(<?php 
						if($nVotes!=false)
							echo $nVotes['cnt'];
					?>)

				</div>
			</footer>
			</div>

		<?php

		if($edit_features_active==true){ ?>
			<div class="column info_right edit-stat">
				<span class="card-edit">
					<a href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Edit
					</a>
				</span>
			
				
				<span class="card-rating">
					<a> </a>
					Statistics
				</span>
				
				<span class="card-rating">
					<a> </a>
					Reservations
				</span>
			</div>

		<?php } ?>
		</a>
	</article>
		
<?php } ?>