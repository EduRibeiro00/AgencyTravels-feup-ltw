<!-- TODO: futuramente passar todos os house cards para aqui -->
<!-- TODO: passar para parametro em vez de estático -->
<?php 
include_once('../templates/tpl_common.php');

// TODO: depois por outros parametros
function draw_horizontal_card($place) { ?>
	<article class="row card">
		<!-- TODO: mudar para carroussel -->
		<a href="#"><img class="hcard-img" src="<?=$place['image']?>"></a>
		<div class="column info">
			<h4><?=$place['title']?></h4>
			<!-- TODO: meter flexbox row, com divs ou spans -->
			<p><span class="card-guests"><?=$place['capacity']?> guests</span><span class="card-bedroom"><?=$place['numRooms']?> bedroom</span><span class="card-bathroom"><?=$place['numBathrooms']?> bathroom</span></p>
			<footer class="row">
				<p>45€/noite</p>
				<div class="card-rating">
					<?php draw_star_rating($place['rating']);?>
					(229)
				</div>
			</footer>
		</div>
	</article>
<?php } ?>