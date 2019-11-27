<?php

include_once('../templates/tpl_common.php');


function draw_comment($comment){ ?>


<section class="Reviews">
    <!--GET PHP INFO-->
    <header class="Review_Header">
        
        <p class="Review_Text_Title"><?=$comment["comment"]?></p>
        
        
        <section class="Review_Header_Content">
            
            
            <img class="Comment_Author_Img" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
            
            <article class="Review_Header_First_Line">
                <!--//TODO: Implement imported from db-->
                <p class="Review_Name_Author"><?=$comment["name"]?></p> 
            </article>
            
            <article class="Review_Header_Second_Line">
                
                <?php  draw_star_rating($comment["stars"])?>
                
                <p class="Review_Author_Location">Location:
                <?php
                    $string_final=$comment["city"].",".$comment["country"];
                    echo $string_final;
                ?>
                </p> 
            </article>

        </section>
    </header>

    <article class="Review_Text">
        
        <p><?=$comment["comment"]?></p>

    </article>
    
    <footer class="Review_Footer">
        
        <p>Published:<?= $comment ["date"]?></p>

    </footer>


</section>

<?php } ?>
