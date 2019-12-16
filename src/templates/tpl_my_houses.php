<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../templates/tpl_common.php');

function draw_my_houses_body($houseOwnerInfo, $myHouses, $numReservs) { 
    draw_confirmation_form();
    ?>
    <?php draw_my_houses_header($houseOwnerInfo, $numReservs);
          list_houses($myHouses, "My_Houses", $houseOwnerInfo['userID']); ?>
<?php }

function draw_my_houses_header($houseOwnerInfo, $numReservs) { ?>
    <header id="my-header">
        <h3>My Houses</h3>
        <section class="row">
			<section class="user_card">
				<a class="user_img" href="../pages/profile_page.php?userID=<?=$houseOwnerInfo['userID']?>">
					<img class="circular-img" src="../assets/images/users/small/<?=$houseOwnerInfo['image']?>">
				</a>
				<a class="user_username" href="../pages/profile_page.php?userID=<?=$houseOwnerInfo['userID']?>">
					<?=$houseOwnerInfo['name']?>
				</a>
				<p>Number of Reservations: <?=$numReservs?></p>
			</section>
			<div id="house_buttons">
			<?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $houseOwnerInfo['userID']) { ?>
					<a class="button" href="my_house_add.php">
						<i class="fas fa-plus"></i>
						Add New Place
					</a>
					<a class="button" href="../pages/places_reservs.php">
						Check Reservations
					</a>
			<?php } ?>
			</div>
        </section>
    </header>
<?php } ?>
