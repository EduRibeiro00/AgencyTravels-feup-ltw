<?php

    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_house_edit_form.php');

    draw_head(['../js/main.js']);
    draw_navbar();


?>


<div id="my_house_edit_container">

    <h2>My House Edit</h2>

    <form action="column" method="post">
        <fieldset>
            <legend>Description</legend>
            <label>Title: <input type="text" name="title"> </label>
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

            Preencher com PHP
        </textarea>


        </fieldset>


        <fieldset>
            <legend>Location</legend>
            <article class="row edit-house-location">
                <label>Address: <input type="text" name="address"> </label>
                <label>City: <input type="text" name="city"> </label>
                <label>Country: <input type="text" name="country"> </label>
                <!--Aplicar REGEX-->
                <label>GPS Coords: <input type="text" name="gps"> </label>

            </article>


        </fieldset>

        <fieldset>
            <legend>House Caracteristics</legend>
            <article class="row edit-house-caracteristics">
                <label>numRooms <input type="text" name="numRooms"></label>
                <label>numBathrooms <input type="text" name="numBathrooms"></label>
                <label>Capacity <input type="text" name="Capacity"></label>
            </article>
        </fieldset>

        <input type="submit" value="Submit">

    </form>
</div>

<?php
    draw_footer();
?>