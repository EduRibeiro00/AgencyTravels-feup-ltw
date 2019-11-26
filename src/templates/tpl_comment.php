<?php

include_once('../templates/tpl_common.php');


function draw_comment(){ ?>


<section class="Reviews">
    <!--GET PHP INFO-->
    <header class="Review_Header">
        
        <p class="Review_Text_Title">Extremamente Desagradavel o Eduardo e os amigos nao se importaram minimamente por deixar o apartamento limpo</p>
        <!--//TODO: Implement imported from db-->
        <section class="Review_Header_Content">

            <img class="Comment_Author_Img" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
            <section class="Review_Header_First_Line">
                <!--//TODO: Implement imported from db-->
                <p class="Review_Name_Author">Peggy S. </p> 
            </section>
            
            
            <section class="Review_Header_Second_Line">
                
                <?php  draw_star_rating(3.8) ?>
                
                <!--//TODO: Implement imported from db-->   
                <p class="Review_Author_Location">San Franscisco</p> 
                <p class="Review_Date">Published:2019</p> 
            </section>
        </section>
    </header>

    <article class="Review_Text">
        <!--//TODO: Implement imported from db-->
        <p>THE TEXT WILL GO HERE</p>

    </article>
    
    <footer class="Review_Footer">
        <!--//TODO: Implement imported from db-->
        <p>Published:2019</p>

    </footer>


</section>

<?php } ?>
