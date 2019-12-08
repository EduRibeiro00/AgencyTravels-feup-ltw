<?php 
include_once('../templates/tpl_reservation_utils.php');

function draw_initial_page() { 
			$ret = getPriceInDate(5, '2019-11-21', '2019-12-02');
			echo $ret;
			 ?>
	<div class="flex-column">
		<div id="greetings">
			<header id="initial">
				<h2>Welcome to definitely <em>not</em> Airbnb</h3>
				<h1>Frase Inspiradora inicial que depois veremos em conjunto ou Visão</h1>
			</header>
		</div>
	</div>
<?php } ?>