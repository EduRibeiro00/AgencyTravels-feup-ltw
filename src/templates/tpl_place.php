<?php

include_once('../templates/tpl_common.php');

//I need this, ok guys? Ass: Ruben Almeida
function first_line() { ?>
    <div id="Place_Info_Container">
        <?php } ?>

        <?php function last_line()
        { ?>
    </div>
<?php } ?>


<?php function draw_my_place_sidebar($house_avg_price,$house_rating, $house_owner_info, $placeID) { ?>

    <aside id="Pop_UP_Fast_Reservation">

        <section id=Pop_UP_Fast_Reservation_Review_Short>
            <p>Price per night</p>
  
            <p id="side_price_per_night"><?=round($house_avg_price,2)?>€</p>

            <?php draw_star_rating($house_rating) ?>

        </section>

        <section id="Pop_UP_Fast_Reservation_Inputs">
            
            <form>
                <?php if($placeID != null) { ?>
                    <input type="hidden" name="placeID" value=<?=$placeID?>>
                <?php } ?>

                <input id="Date_Start" type="date" name="check_in_date">
                <input id="Date_End" type="date" name="check_out_date">
                <br>
                <input id="Book_Submit_Button" type="submit">

            </form>

        </section>

        <section id="Owner_info">
            <a href="../pages/profile_page.php?userID=<?=$house_owner_info['userID']?>">
                <img id="Owner_Img" class="circular-img" src="../assets/images/users/small/<?=$house_owner_info['image']?>">
            </a>
            <span><?=$house_owner_info['name']?></span>
            <form>
                <button id="Button_Send_Message">
                    Send Message
                </button>
            </form>

        </section>

    </aside>

<?php } ?>


<?php

function draw_my_place_icon_desc($house_name, $house_numRooms, $house_capacity, $house_numBathrooms, $house_description)
{ ?>

    <article id="House_Info">

        <header><?= $house_name ?></header>

        <ul id="Pictographic_Info">
            <li>
                <i class="fas fa-bed"></i>
                Number of Rooms <?= $house_numRooms ?>

            </li>
            <li>
                <i class="fas fa-user"></i>
                Capacity <?= $house_capacity ?>

            </li>
            <li>
                <i class="fas fa-toilet"></i>
                Bathrooms <?= $house_numBathrooms ?>

            </li>

        </ul>

        <p id="House_Description"> <?= $house_description ?></p>
    </article>

<?php }


//TODO:Implement Google MAPS
function draw_my_place_location($house_address_full, $house_gpsCoords)
{ ?>

    <article id="Google_Maps_Widget_Container">

        <header>Location</header>

        <section id="Google_Maps_Widget">

            <img id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">

        </section>

        <footer>
            <p>Address:<?= $house_address_full ?></p>
            <p>GPS_Coords:<?= $house_gpsCoords ?></p>
        </footer>

    </article>

<?php } ?>