<?php

    function draw_similar_offer(){?>

        <article class="Similar_Offer_Container">

        <img class="Similar_Offer_img" src="https://si.wsj.net/public/resources/images/B3-DM067_RIGHTS_IM_20190319162958.jpg">
        <!--Extract this information from the database-->
        <p class="Similar_Offer_title">Casa de cima</p>
        <p class="Similar_Offer_Avg_Price">499€/night</p>
        <!--//TODO:IMPLEMENT THE STARS-->
        <p class="Review_Start_Rating"></p>

        </article>



<?php } ?>


<?php

function draw_similar_offer_slide_show(){ ?>

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


<?php } ?>



