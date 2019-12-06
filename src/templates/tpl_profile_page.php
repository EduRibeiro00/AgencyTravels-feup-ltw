<?php
    include_once('../templates/tpl_house_min.php'); 

    function draw_profile_info($user_info, $user_places, $city_image) { ?>
        <main id="profile-page">
          <section id="banner-image">
                <img src="../assets/images/places/big/<?=$city_image[0]['image']?>">
          </section>

          <section class="profile-info">
                <div id="profile-image">
                    <img class="circular-img" src="../assets/images/users/medium/<?=$user_info['image']?>">
                </div>

                <div id="mail-icon" class="circular-cropper">
                    <a href="mailto:<?=$user_info['email']?>">
                        <img src="../assets/images/others/mail_icon.jpg">
                    </a>
                </div>
                
                <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $user_info['userID']) { ?>
                    <div class="edit-profile"> 
                        <a class="button" href="../pages/profile_edit.php" >Edit Profile</a>
                    </div>
                <?php } ?>

                <section class="profile-info-fields">
                    <p id="username"><strong>Username: </strong><?=$user_info['username']?></p>
                    <p id="name"><strong>Name: </strong><?=$user_info['name']?></p>
                    <p id="email"><strong>Email: </strong><?=$user_info['email']?></p>
                    <p id="birthdate"><strong>Birth date: </strong><?=$user_info['birthDate']?></p>
                    <p id="gender"><strong>Gender: </strong><?=$user_info['gender']?></p>
                    <p id="location"><strong>Location: </strong><?=$user_info['city']?>, <?=$user_info['country']?></p>
                </section>
          </section>

            <section class="bio">
                <h3>About me:</h3>
                <?php if($user_info['description'] == null || $user_info['description'] == '') { ?>
                    <p><em>No description available</em></p>
                <?php } 
                else { ?>
                    <p><?=$user_info['description']?></p>
                <?php }?>
            </section>  

            <?php
                $title = $user_info['username'] . "'s places";
                $link = 'my_houses.php?userID=' . $user_info['userID'];
                draw_place_listing($title, $user_places, $link);
            ?>

            <section class="place-comments">
                <h3>Some reviews of <?=$user_info['username']?> 's places</h3>
                <p> TODO: desenhar comments </p>
            </section>

        </main>
<?php } ?>
