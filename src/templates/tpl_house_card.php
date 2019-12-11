<?php 
include_once('../templates/tpl_common.php');
include_once('../database/db_places.php');
include_once('../includes/reservation_utils.php');
include_once('../templates/tpl_place.php');

function draw_horizontal_card($place, $drawingOption, $userID) { ?>
	<article class="row card">
		<!-- TODO: mudar para carroussel (para ja apenas a utilizar a 1a imagem, mas $place ja as tem todas) -->
		<a class="row" href="../pages/place_info.php?place_id=<?=$place['placeID']?>">
			<img class="hcard-img" src="../assets/images/places/medium/<?=$place['images'][0]['image']?>">
			<div class="column info">
			<h4><?=$place['title']?></h4>
			<?php draw_place_details($place['numRooms'], $place['capacity'], $place['numBathrooms']); ?>
			<footer class="row">
				<?php if(isset($place['price']) && $place['price'] != null) { ?>
					<p><?=$place['price']?>€ / night</p>
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

<?php 	if($drawingOption == 'My_Houses' && isset($_SESSION['userID']) && $_SESSION['userID'] == $userID) { ?>
			<section class="column card-options">
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Edit
				</a>
			
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Remove
				</a>
				
				<a class="button" href="my_house_edit.php?placeID=<?=$place['placeID']?>"> 
					Reservations
				</a>
			</section>
   <?php } 
		 else if($drawingOption == "My_Reservs" && isset($_SESSION['userID']) && $_SESSION['userID'] == $userID) { ?>
			<section class="column reserv-dates">
				<div>
					<p>Reservation from:</p>
					<p><?=$place['startDate']?></p>
		 		</div>
				<div>
				 	<p>Until:</p>
					<p><?=$place['endDate']?></p>
		 		</div>
		 	</section>

			<section class="column card-options">

				<?php  
					if(!canCancelReservation($place['startDate'])) { ?>
						<a class="button cancel-button" href="#" data-id="<?=$place['reservationID']?>"> 
							Cancel
						</a>
			   <?php } 
			   
					 if(canReviewPlace($place['endDate'], $place['reviewID'])) { ?>
						<a class="button" href="place_info.php?place_id=<?=$place['placeID']?>"> 
							Review place
						</a> 
					<?php } ?>
			</section>
		<?php } ?>
	
	</article>
<?php }
