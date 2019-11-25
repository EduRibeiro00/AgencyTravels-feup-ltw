
<?php
    
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_comment.php');
    include_once('../templates/tpl_similar_offer.php');
	draw_head();
	draw_navbar();
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
            <!-- For stars
                <p></p>
                <?php  ?>
            --> 

            <!-- For Average
                <p></p>
                <?php  ?>
            --> 

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
        
        <header>Casa de Cima</header>
        
        <ul id="Pictographic_Info">
            <li>
                <img class="info_icon" src="https://image.flaticon.com/icons/png/512/2/2144.png">
                Number of Rooms
                <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                Capacity
                    <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                Garage
                    <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                Bathrooms
                    <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            
        </ul>
        
        <p id="House_Description">  VIVA O SPORTING CLUBE PORTUGAL</p>


    </article>

    <!--//TODO:Implement Google MAPS-->

    <article id="Google_Maps_Widget_Container">

        <header>Location</header>

        <section id="Google_Maps_Widget">
            
            <img id=Google_Maps_Img src="http://gnomo.fe.up.pt/~up201704618/Screenshot_2019-11-20%20Oporto4all%20-%20Trindade,%20Porto,%20Portugal.png">

        </section>

        <footer>
            <p>Address:</p>
            <p>ZIP_CODE:</p>
        </footer>

    </article>



    <article id="Reviews_Container">

            <header>
                <p>Revisions</p>
                <!-- For stars
                <p></p>
                --> 
                <?php draw_comment() ?>
                <?php draw_comment() ?>
            
            </header>
        
            <!--
                Imprimir em PHP da DB
            -->
            
        
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
