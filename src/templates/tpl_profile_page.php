<?php
    include_once('../templates/tpl_house_min.php'); 
    include_once('../templates/tpl_comment.php');
	include_once('../includes/google_maps.php');
	include_once('../includes/reservation_utils.php');

    function draw_profile_info($user_info, $user_places, $city_image, $user_place_comments) { ?>
        <main id="profile-page">
          <section id="banner-image">
                <img src=<?=$city_image?>>
          </section>

          <section class="profile-info row">
                <div id="profile-image">
                    <img class="circular-img" src="../assets/images/users/medium/<?=$user_info['image']?>">
                </div>

				<a id="mail-icon" href="mailto:<?=htmlspecialchars($user_info['email'])?>">
					<i class="far fa-envelope"></i>
				</a>
                
                <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $user_info['userID']) { ?>
                    <div class="edit-profile"> 
                        <a class="button" href="../pages/profile_edit.php" >Edit Profile</a>
                    </div>
                <?php } ?>

                <section class="profile-info-fields">
                    <p id="username"><strong>Username: </strong><?=htmlspecialchars($user_info['username'])?></p>
                    <p id="name"><strong>Name: </strong><?=htmlspecialchars($user_info['name'])?>, <i class="fas <?=genderToIcon(htmlspecialchars($user_info['gender']))?>"></i></p>
                    <p id="birthdate"><strong>Age: </strong><?=date_diff(date_create($user_info['birthDate']), date_create('today'))->y;?> yo</p>
                </section>
          </section>

            <section class="bio">
                <h3>About me:</h3>
                <?php if($user_info['description'] == null || $user_info['description'] == '') { ?>
                    <p><em>No description available</em></p>
                <?php } 
                else { ?>
                    <p><?=htmlspecialchars($user_info['description'])?></p>
				<?php }?>
			</section> 
			<section class="loc">
				<h3>Location:</h3>
				<p id="location"><?=htmlspecialchars($user_info['city'])?>, <?=htmlspecialchars($user_info['country'])?></p>

				<?php initGoogleMaps(); ?>
			</section>
                

            <?php
                $title = $user_info['username'] . "'s places";
                $link = 'my_houses.php?userID=' . $user_info['userID'];
                draw_place_listing($title, $user_places, $link);
            ?>

            <section class="place-comments">
                <h3>Some reviews of <?=htmlspecialchars($user_info['username'])?>'s places</h3>
                
                <?php if($user_place_comments == null || count($user_place_comments) == 0) { ?>
                    <p><em>No reviews available</em></p>
                <?php }
                    else {
                        foreach($user_place_comments as $comment) {
                            draw_comment($comment, true);
                         }
                    } ?>
            </section>
        </main>
<?php } 

function genderToIcon($gender){
	switch ($gender) {
		case 'M':
			return "fa-mars";
		case 'F':
			return "fa-venus";
		case 'O':
			return "fa-transgender";
		default:
			return "fa-transgender-alt";
	}
}

?>
