<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../templates/tpl_common.php');

function draw_my_reservs_body($user_info, $myReservations) { 
    draw_confirmation_form();
    ?>
    <?php draw_my_reservs_header($user_info);
          list_houses($myReservations, "My_Reservs", $user_info['userID']); ?>
<?php } 

function draw_my_reservs_header($user_info) { ?>
    <header id="my-header">
		<h3>My Reservations</h3>
		<div class="row">
			<a class="user_img" href="../pages/profile_page.php?userID=<?=$user_info['userID']?>">
				<img class="circular-img" src="../assets/images/users/small/<?=$user_info['image']?>">
			</a>
			<a class="user_username" href="../pages/profile_page.php?userID=<?=$houseOwnerInfo['userID']?>">
				<?=$user_info['name']?>
			</a>
		</div>
    </header>
<?php } ?>