<?php 
include_once('../templates/tpl_house_card.php');
				
// TODO ver parametros depois
function list_houses_result() { ?>
	<main class="row">
		<section id="house_results">
			<?php 
			// TODO: depois por ciclo certo pelos resultados
			for($i = 0; $i < 5; $i++)
				draw_horizontal_card($i + 0.5);
			?>
		</section>
		<section id="map">
			<img src="https://via.placeholder.com/1024?text=Maps+Placeholder">
		</section>
	</main>
<?php } ?>