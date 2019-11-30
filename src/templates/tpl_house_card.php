<!-- TODO: futuramente passar todos os house cards para aqui -->
<!-- TODO: passar para parametro em vez de estático -->
<?php 
include_once('../templates/tpl_common.php');

// TODO: depois por outros parametros
function draw_horizontal_card($rating,$edit_features_active=false,$near_reservations_active=false) { ?>
	<article class="row card">
		<!-- TODO: mudar para carroussel -->
		<a href="#"><img class="hcard-img" src="https://via.placeholder.com/460?text=Should+Be+Carroussel"></a>
		<div class="column info">
			<h4>Nome da Casa Muito fixe</h4>
			<!-- TODO: meter flexbox row, com divs ou spans -->
			<p><span class="card-guests">5 guests</span><span class="card-bedroom">1 bedroom</span><span class="card-bathroom">1 bathroom</span></p>
			<footer class="row">
				<p>45€/noite</p>
				<div class="card-rating">
					<?php draw_star_rating($rating);?>
					(229)
				</div>
			</footer>
		</div>

		<?php

		if($edit_features_active==true){ ?>
			<div class="column info edit-stat">
				
				<span class="card-edit">Edit
					<a> </a>
				</span>
				<span class="card-stats">Stats
					<a> </a>
				</span>
					
			</div>

		<?php } ?>

	</article>
		


<?php } ?>