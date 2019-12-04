<?php

include_once('../templates/tpl_common.php');
include_once('../database/db_myplace.php');

function draw_form($placeId)
{ ?>

    <section id="place_edit_form">

        <form>
            <?php if($placeId != null) { ?>
                <input type="hidden" name="placeID" value=<?=$placeId?>>
            <?php } ?>
            <fieldset>
                <legend>Description</legend>
                <label>Title: <input type="text" name="title" value="<?= get_house_title($placeId)['title']; ?>"> </label>
            </fieldset>

            <fieldset>
                <legend>Pictures</legend>
                <!--Vai levar AJAX para apagar as photos-->
                <!--Vai levar AJAX para caregar as novas photos uploaded-->
                <article class="row edit-house-imgs">

                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                    <input type="file" name="new_photo">

                </article>

            </fieldset>

            <fieldset>
                <legend>Description</legend>
                <textarea name="description" rows="10" cols="100">
            <?= get_house_description($placeId)['description']; ?>
            </textarea>


            </fieldset>


            <fieldset>
                <legend>Location</legend>
                <article class="row edit-house-location">
                    <label>Address: <input type="text" name="address" value="<?= get_house_address($placeId)['address']; ?>"> </label>
                    <label>City: <input type="text" name="city" value="<?= get_house_address_city($placeId)['city']; ?>"> </label>
                    <label>Country: <input type="text" name="country" value="<?= get_house_address_country($placeId)['country']; ?>"> </label>
                </article>


            </fieldset>

            <fieldset>
                <legend>House Caracteristics</legend>
                <article class="row edit-house-caracteristics">
                    <label>Number of Rooms<input type="text" name="numRooms" value=" <?= get_house_numRooms($placeId)['numRooms']; ?>"></label>
                    <label>Number of Bathrooms<input type="text" name="numBathrooms" value=" <?= get_house_numBathrooms($placeId)['numBathrooms']; ?>"></label>
                    <label>Capacity<input type="text" name="capacity" value=" <?= get_house_capacity($placeId)['capacity']; ?>"></label>
                </article>
            </fieldset>

            <input type="submit" value="Submit">

        </form>


    </section>



<?php  } ?>