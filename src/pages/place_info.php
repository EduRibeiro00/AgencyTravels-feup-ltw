
<?php
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_comment.php');
    include_once('../templates/tpl_similar_offer.php');
    include_once('../database/db_myplace.php');
	draw_head();
    draw_navbar();
    
    $place_id=$_GET['place_id'];

    $house_name=get_house_title($place_id)["title"];
    $house_rating=get_house_rating($place_id)["rating"];
    $house_description=get_house_description($place_id)["description"];
    $house_numRooms=get_house_numRooms($place_id)["numRooms"];
    $house_capacity=get_house_capacity($place_id)["capacity"];
    $house_numBathrooms=get_house_numBathrooms($place_id)["numBathrooms"];
    $house_address=get_house_address($place_id)["address"];
    $house_address_city=get_house_address_city($place_id)["city"];
    $house_address_country=get_house_address_country($place_id)["country"];
    $house_gpsCoords=get_house_gpsCoords($place_id)["gpsCoords"];
    $house_comments=get_house_comments($place_id);

    //Adress string parsing

    $house_address_full=$house_address.",".$house_address_city.",".$house_address_country;
    
    
?>

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


<div id="Place_Info_Container">
    <!--
        //TODO: How to code Rating Input Stars??? PHP
    -->
    <aside id="Pop_UP_Fast_Reservation">      
        
        <section id=Pop_UP_Fast_Reservation_Review_Short>
            <p>Price per night</p>
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

    <article id="House_Info">
        
        <header><?=$house_name?></header>
        
        <ul id="Pictographic_Info">
            <li>
                <img class="info_icon" src="https://image.flaticon.com/icons/png/512/2/2144.png">
                Number of Rooms <?=$house_numRooms?>
                
            </li>
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                Capacity <?=$house_capacity?>
                
            </li>
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                Bathrooms <?=$house_numBathrooms ?>
                    
            </li>
            
        </ul>
        
        <p id="House_Description">  <?=$house_description?></p>


    </article>

    <!--//TODO:Implement Google MAPS-->

    <article id="Google_Maps_Widget_Container">

        <header>Location</header>

        <section id="Google_Maps_Widget">
            
            <img id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">

        </section>

        <footer>
            <p>Address:<?=$house_address_full?></p>
            <p>GPS_Coords:<?=$house_gpsCoords?></p>
        </footer>

    </article>



    <article id="Reviews_Container">

            <header>
                <p>Revisions</p>
                <?php draw_star_rating($house_rating)?>
                
            </header>
            
            <?php  
                foreach($house_comments as $comment)
                    draw_comment($comment);    
            ?>
            
        
        
    </article>

    <article id="Avaiabilities">

        <section id="Avaiabilities_Calendars">
            
            <p>Check In</p>
            
            <form action="#s" method="GET">
                <input id="Avaiabilities_Date_Start" type="date">
            </form>


            <form>
                <p>Check Out</p>
                <input id="Avaiabilities_Date_End" type="date">    

            </form>

        </section>

        <section id="Avaiabilities_Number_Guests">

            <p>Select the Number of Guests</p>

            <form action="#s" method="GET">
                <select name="guests_number">
                    <option value="1">1 Guest</option>
                    <option value="2">2 Guests</option>
                    <option value="3">3 Guests</option>
                </select>
                
            </form>
            
        </section>
        

    </article>


    <article id="Similar_Offers_Container">

        <section id="Similar_Offers_Left">
            <?php draw_similar_offer() ?>
        </section>

        <section id="Similar_Offers_Center">
            <?php draw_similar_offer() ?>
        </section>

        <section id="Similar_Offers_Right">
            <?php draw_similar_offer() ?>
        </section>        
        

    </article>

</div>


<?php draw_footer(); ?>
