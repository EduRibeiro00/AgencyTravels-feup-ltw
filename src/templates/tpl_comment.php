<?php
include_once('../templates/tpl_common.php');
include_once('../templates/tpl_cards.php');
include_once('../database/db_user.php');

function draw_comment($comment, $linkToPlace = false){ ?>
    <article class="review">
		<?php if($linkToPlace) { ?>
          <a class="overlay_anchor" href="../pages/place_info.php?place_id=<?=$comment['placeID']?>"></a>
        <?php } ?>
		<header>
			<?php draw_user_card($comment, 'rating') ?>
		</header>
		<p><?=$comment["comment"]?></p>
		<footer>
			<p>Published: <?=$comment["date"]?></p>
		</footer>
    </article>

<?php } ?> 

<?php function draw_all_comments($house_rating, $house_comments) { 
	?>
    <article id="reviews">
    <header>
        <h3><?=count($house_comments)?> Reviews</h3>
        <?php draw_star_rating($house_rating)?>
    </header>
    <?php  
        foreach($house_comments as $comment)
            draw_comment($comment);    
    ?>
    </article>
<?php } ?>



