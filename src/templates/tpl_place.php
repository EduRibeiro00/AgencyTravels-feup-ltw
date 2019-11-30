<?php  

include_once('../templates/tpl_common.php');

//I need this, ok guys? Ass: Ruben Almeida
function first_line(){ ?>
<div id="Place_Info_Container">
    <?php } ?>

    <?php function last_line(){ ?>
</div>
<?php } ?>

<?php function draw_my_place_sidebar($house_rating){?>

<aside id="Pop_UP_Fast_Reservation">

    <section id=Pop_UP_Fast_Reservation_Review_Short>
        <p>Price per night</p>
        <!--
    Extract from the database
    -->
        <p>65€</p>

        <?php  draw_star_rating($house_rating) ?>

    </section>

    <section id="Pop_UP_Fast_Reservation_Inputs">
        <form action="#s" method="GET">

            <input id="Date_Start" type="date">
            <input id="Date_End" type="date">
            <br>
            <input id="Book_Submit_Button" type="submit">

        </form>

    </section>

    <section id="Owner_info">
        <img id="Owner_Img" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">

        <span>Moussa Marega</span>
        <br>
        <form action="#" method="GET">
            <button id="Button_Send_Email">
                Send Email
            </button>
        </form>

    </section>

</aside>

<?php } ?>


<?php

function draw_my_place_icon_desc($house_name,$house_numRooms,$house_capacity,$house_numBathrooms,$house_description){ ?>

<article id="House_Info">

    <header><?=$house_name?></header>

    <ul id="Pictographic_Info">
        <li>
            <i class="fas fa-bed"></i>
            Number of Rooms <?=$house_numRooms?>

        </li>
        <li>
            <i class="fas fa-user"></i>
            Capacity <?=$house_capacity?>

        </li>
        <li>
            <i class="fas fa-toilet"></i>
            Bathrooms <?=$house_numBathrooms ?>

        </li>

    </ul>

    <p id="House_Description"> <?=$house_description?></p>
</article>

<?php } 

//TODO:Implement Google MAPS

function draw_my_place_location($house_address_full,$house_gpsCoords){ ?>

<article id="Google_Maps_Widget_Container">

    <header>Location</header>

    <section id="Google_Maps_Widget">

        <img id=Google_Maps_Img
            src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">

    </section>

    <footer>
        <p>Address:<?=$house_address_full?></p>
        <p>GPS_Coords:<?=$house_gpsCoords?></p>
    </footer>

</article>

<?php } ?>

<?php function draw_myplace_slideshow(){ ?>

<div id="carousel_container">
    <div class="carousel">
        <ul class="slides">
            <input type="radio" name="radio-buttons" id="img-1" checked />
            <li class="slide-container">
                <div class="slide-image">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                </div>
                <div class="carousel-controls">
                    <label for="img-3" class="prev-slide">
                        <span>&lsaquo;</span>
                    </label>
                    <label for="img-2" class="next-slide">
                        <span>&rsaquo;</span>
                    </label>
                </div>
            </li>
            <input type="radio" name="radio-buttons" id="img-2" />
            <li class="slide-container">
                <div class="slide-image">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/06/62/40/5c.jpg">
                </div>
                <div class="carousel-controls">
                    <label for="img-1" class="prev-slide">
                        <span>&lsaquo;</span>
                    </label>
                    <label for="img-3" class="next-slide">
                        <span>&rsaquo;</span>
                    </label>
                </div>
            </li>
            <input type="radio" name="radio-buttons" id="img-3" />
            <li class="slide-container">
                <div class="slide-image">
                    <img src="http:////tap1.fkimg.com/media/vr-splice-j/06/0a/ff/92.jpg">
                </div>
                <div class="carousel-controls">
                    <label for="img-2" class="prev-slide">
                        <span>&lsaquo;</span>
                    </label>
                    <label for="img-1" class="next-slide">
                        <span>&rsaquo;</span>
                    </label>
                </div>
            </li>
            <div class="carousel-dots">
                <label for="img-1" class="carousel-dot" id="img-dot-1"></label>
                <label for="img-2" class="carousel-dot" id="img-dot-2"></label>
                <label for="img-3" class="carousel-dot" id="img-dot-3"></label>
            </div>
        </ul>
    </div>
</div>

<?php } ?>