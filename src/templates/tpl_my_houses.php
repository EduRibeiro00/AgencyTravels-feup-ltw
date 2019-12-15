<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../templates/tpl_common.php');

function draw_my_houses_body($houseOwnerInfo, $myHouses, $numReservs) { 
    draw_confirmation_form();
    ?>
    <main id="my-container">
    <?php draw_my_houses_header($houseOwnerInfo, $numReservs);
          list_houses($myHouses, "My_Houses", $houseOwnerInfo['userID']); ?>
    </main>
<?php }

function draw_my_houses_header($houseOwnerInfo, $numReservs) { ?>
    <header>
        <h3>My Houses</h3>
        <section id="my-header" class="row">
            <div id="my-info" class="row">
                <div class="row">
                    <a class="circ-img-link" href="../pages/profile_page.php?userID=<?=$houseOwnerInfo['userID']?>">
                        <img class="circular-img" src="../assets/images/users/small/<?=$houseOwnerInfo['image']?>">
                    </a>
                    <p><?=$houseOwnerInfo['name']?></p>
                </div>
                <p>Number of Reservations: <?=$numReservs?></p>
            </div>
            <p>Number of Reservations: <?=$numReservs?></p>
        </div>
        <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $houseOwnerInfo['userID']) { ?>
            <a id="new-place-button" class="button" href="my_house_add.php">
                <i class="fas fa-plus"></i>
                Add New Place
            </a>
        <?php } ?>

                    <a id="place-reservs-button" class="button" href="../pages/places_reservs.php">
                        Check Reservations
                    </a>
                </aside>
            <?php } ?>
        </section>
    </header>
 <?php } ?>
