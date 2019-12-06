<?php
include_once('../templates/tpl_common.php');

function draw_comment($comment, $linkToPlace = false){ ?>
    <article class="review">
        <?php if($linkToPlace) { ?>
          <a href="../pages/place_info.php?place_id=<?=$comment['placeID']?>">
        <?php } ?>
            <header>
                <?php if(!$linkToPlace) { ?>
                    <a href="../pages/profile_page.php?userID=<?=$comment['userID']?>">
                <?php } ?>
    	    	        <img class="Comment_Author_Img circular-img" src="../assets/images/users/small/<?=$comment['image']?>">
                <?php if(!$linkToPlace) { ?>    
                    </a>
                <?php } ?>
                <p><?=$comment["name"]?></p> 
    	    	<?php draw_star_rating($comment["stars"])?>
            </header>
            <p><?=$comment["comment"]?></p>
            <footer>
                <p>Published: <?=$comment["date"]?></p>
            </footer>
        <?php if($linkToPlace) { ?>
            </a>
        <?php } ?>
    </article>

<?php } ?> 

<?php function draw_all_comments($house_rating,$house_comments) { ?>
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



