<?php
    include_once('../templates/tpl_house_min.php'); 
    include_once('../templates/tpl_comment.php');
    include_once('../includes/google_maps.php');

    function draw_profile_info($user_info, $user_places, $city_image, $user_place_comments) { ?>
        <main id="profile-page">
          <section id="banner-image">
                <img src="../assets/images/places/big/<?=$city_image[0]['image']?>">
          </section>

          <section class="profile-info">
                <div id="profile-image">
                    <img class="circular-img" src="../assets/images/users/medium/<?=$user_info['image']?>">
                </div>

                <div id="mail-icon" class="circular-cropper">
                    <a href="mailto:<?=htmlspecialchars($user_info['email'])?>">
                        <img src="../assets/images/others/mail_icon.jpg">
                    </a>
                </div>
                
                <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $user_info['userID']) { ?>
                    <div class="edit-profile"> 
                        <a class="button" href="../pages/profile_edit.php" >Edit Profile</a>
                    </div>
                <?php } ?>

                <section class="profile-info-fields">
                    <p id="username"><strong>Username: </strong><?=htmlspecialchars($user_info['username'])?></p>
                    <p id="name"><strong>Name: </strong><?=htmlspecialchars($user_info['name'])?></p>
                    <p id="email"><strong>Email: </strong><?=htmlspecialchars($user_info['email'])?></p>
                    <p id="birthdate"><strong>Birth date: </strong><?=htmlspecialchars($user_info['birthDate'])?></p>
                    <p id="gender"><strong>Gender: </strong><?=htmlspecialchars($user_info['gender'])?></p>
                    <p id="location"><strong>Location: </strong><?=htmlspecialchars($user_info['city'])?>, <?=htmlspecialchars($user_info['country'])?></p>
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
<?php } ?>
