<?php

include_once('../templates/tpl_common.php');


function draw_comment($comment){ ?>
<article class="review">
    <header>
		<img class="Comment_Author_Img circular-img" src="../assets/images/users/small/<?=$comment['image']?>">
		<p><?=$comment["name"]?></p> 
		<?php draw_star_rating($comment["stars"])?>
    </header>
    <p><?=$comment["comment"]?></p>
    <footer>
        <p>Published: <?=$comment["date"]?></p>
    </footer>
</article>

<?php 

} 

function draw_all_comments($house_rating,$house_comments){ ?>
    <article id="reviews">
    <header>
        <p>Reviews</p>
        <?php draw_star_rating($house_rating)?>
    </header>
    <?php  
        foreach($house_comments as $comment)
            draw_comment($comment);    
    ?>
    </article>
<?php } ?>



