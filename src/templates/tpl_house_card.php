<!-- TODO: futuramente passar todos os house cards para aqui -->
<!-- TODO: passar para parametro em vez de estático -->
<?php function draw_horizontal_card() { ?>
	<!-- TODO: ver se article, div  -->
	<article class="row card">
		<!-- TODO: mudar para carroussel -->
		<a href="#"><img class="hcard-img" src="https://via.placeholder.com/460?text=Should+Be+Carroussel"></a>
		<article class="column info">
			<h4>Nome da Casa Muito fixe</h4>
			<!-- TODO: ver isto, n pode ser 'p' pq depois n caem tipo spans -->
			<p><span class="card-guests">5 guests</span><span class="card-bedroom">1 bedroom</span><span class="card-bathroom">1 bathroom</span></p>
			<footer class="row">
				<p>45€/noite</p>
				<div class="card-rating">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
					(229)
				</div>
			</footer>
		</article>
	</article>
<?php } ?>