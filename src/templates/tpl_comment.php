<?php
include_once('../templates/tpl_common.php');
include_once('../templates/tpl_cards.php');
include_once('../database/db_user.php');
include_once('../includes/input_validation.php');

function draw_comment($comment, $linkToPlace, $commentReplies = false){ ?>
    <article class="review" data-reviewID="<?=$comment['reviewID']?>">
		<?php if($linkToPlace) { ?>
          <a class="overlay_anchor" href="../pages/place_info.php?place_id=<?=$comment['placeID']?>"></a>
        <?php } ?>
		<header>
			<?php draw_user_card($comment, 'rating'); ?>
		</header>
		<p><?=htmlspecialchars($comment["comment"])?></p>
		<footer>
			<p>Published: <?=$comment["date"]?></p>
		</footer>

           <?php if($commentReplies) {  ?>
                <section class="comment-replies">
                    <?php foreach($comment['replies'] as $reply) { ?>
                        <article class="reply" data-replyID="<?=$reply['replyID']?>">
                            <header class="row">
                                <a href="../pages/profile_page.php?userID=<?=$reply['userID']?>">
                                    <img class="reply-author-img circular-img" src="../assets/images/users/small/<?=$reply['image']?>">
                                </a>
                                <a href="../pages/profile_page.php?userID=<?=$reply['userID']?>">
                                    <p><?=htmlspecialchars($reply["username"])?></p>
                                </a>
                            </header>
                            <p><?=htmlspecialchars($reply["comment"])?></p>
                            <footer>
                                <p>Published: <?=$reply["date"]?></p>
                            </footer>
                        </article>
                    <?php } ?>
                </section>

                <?php if(isset($_SESSION['userID']) && validatePosIntValue($_SESSION['userID']) && getUserInformation($_SESSION['userID']) !== false) { ?>
                    <section class="add-reply-section">
                        <p>Add a reply:</p>
                        <form class="reply-form row">
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

                            <label for="reply-desc">Comment:
                                <textarea rows="5" cols="50" name="reply-desc" required></textarea>
                            </label>
                            <input class="button" type="submit" value="Submit">
                        </form>
                    </section>
                <?php } ?>
          <?php } ?>

    </article>

<?php } ?> 

<?php function draw_all_comments($house_rating, $house_comments) { ?>
    <article id="reviews">
    <header>
        <h3><?=count($house_comments)?> Reviews</h3>
		<?php draw_star_rating($house_rating)?>
		<p><?=number_format($house_rating, 1); ?>/5.0</p>
    </header>
    <?php  
        foreach($house_comments as $comment)
            draw_comment($comment, false, true);    
    ?>
    </article>
<?php } ?>



