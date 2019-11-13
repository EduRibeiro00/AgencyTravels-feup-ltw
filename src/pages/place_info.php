
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
                <input id="Book_Submit_Button" type="submit">
        
            </form>

        </section>

        <section id="Owner_info">
                <p>Owner Name:</p>
                <img id="Owner_Img" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
                
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
                <p>Number of Rooms</p>
                <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            
            <li>
                <img class="info_icon" src="https://cdn1.vectorstock.com/i/1000x1000/93/40/bed-icon-symbol-simple-design-vector-26279340.jpg">
                <p>Capacity</p>
                    <!--PHP TO RETREVIE THE NUMBER OF ROOM-->
            </li>
            

        </section>
    </article>

    <!--//TODO:Implement Google MAPS-->

    <article id="Google_Maps_Widget">

        <header>Location</header>

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
                <?php draw_comment() ?>
                <?php draw_comment() ?>

            </header>
        
            <!--
                Imprimir em PHP da DB
            -->
            
        
    </article>

    <article id="Avaiabilities">

            <form action="#s" method="GET">
                    <input id="Date_Start" type="date">
                    <input id="Date_End" type="date">    
            </form>

            <a id="Left_Slider_Avaiability" href="#"> Slider Avaiability Left</a>
            <a id="Right_Slider_Avaiability" href="#"> Slider Avaiability Right</a>


    </article>


    <article id="Similar_Offers">

        <ul>
            <li class="Similar_Offers_Item"><?php draw_similar_offer() ?></li>
            <li class="Similar_Offers_Item"><?php draw_similar_offer() ?></li>
                
        </ul>

    </article>

</div>


<?php draw_footer(); ?>
