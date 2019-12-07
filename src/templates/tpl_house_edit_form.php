<?php

include_once('../templates/tpl_common.php');
include_once('../database/db_places.php');

function draw_form($place)
{ 
    
    $city=getPlace($place['placeID']);
    ?>

    <section id="place_edit_form">

        <form>
           
        <?php if($place != null) { ?>
                <input type="hidden" name="placeID" value=<?=$place['placeID']?>>
            <?php } ?>

            <fieldset>
                <legend>Description</legend>
                <label>Title: <input type="text" name="title" value="<?= $place['title']; ?>"> </label>
            </fieldset>

            <fieldset>
                <legend>Pictures</legend>
                <!--Vai levar AJAX para apagar as photos-->
                <!--Vai levar AJAX para caregar as novas photos uploaded-->
                <article class="row edit-house-imgs">

                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <input type="file" accept="image/*" name="new_photo" multiple>

                </article>

            </fieldset>

            <fieldset>
                <legend>Description</legend>
                <textarea name="description" rows="10" cols="100">
            <?= $place['description']; ?>
            </textarea>


            </fieldset>

            <fieldset>
                <legend>Location</legend>
                <article class="row edit-house-location">
                    <label>Address: <input type="text" name="address" value="<?= $place['address']; ?>"> </label>
                    <label>City: <input type="text" name="city" value="<?= $city['city']; ?>"> </label>
                    <label>Country: <input type="text" name="country" value="<?= $city['country']; ?>"> </label>
                </article>


            </fieldset>

            <fieldset>
                <legend>House Caracteristics</legend>
                <article class="row edit-house-caracteristics">
                    <label>Number of Rooms<input type="text" name="numRooms" value=" <?= $place['numRooms']; ?>"></label>
                    <label>Number of Bathrooms<input type="text" name="numBathrooms" value=" <?= $place['numBathrooms']; ?>"></label>
                    <label>Capacity<input type="text" name="capacity" value=" <?= $place['capacity']; ?>"></label>
                </article>
            </fieldset>

            <input type="submit" value="Submit">

        </form>


    </section>



<?php  } ?>