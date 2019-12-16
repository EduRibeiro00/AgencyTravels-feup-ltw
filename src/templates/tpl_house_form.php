<?php
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../includes/google_maps.php');

function draw_form($place = null, $edit_menu = false, $all_locations) {
    
    $userID = $_SESSION['userID'];

    if ($edit_menu == true) {
        $placeID = $place['placeID'];
        $title = $place['title'];
        $address = $place['address'];
        $numRooms = $place['numRooms'];
        $numBathrooms = $place['numBathrooms'];
        $capacity = $place['capacity'];
        $gpsCoords=$place['gpsCoords'];
        $location = getPlaceLocation($placeID)['locationID'];
        $imagearray = getPlaceImages($place['placeID']);
        $imagearray_lenght = count($imagearray);
        $imagePreview_small = "../assets/images/places/small/";
        $imagePreview_medium = "../assets/images/places/medium/";
        $description=$place['description'];

    } else {
        $title = '';
        $address = '';
        $numRooms = '';
        $numBathrooms = '';
        $capacity = '';
        $imagearray_lenght = 0;
        $location = '';
        $gpsCoords='';
        $description=''; 
        $imagePreview_small = "../assets/images/places/small/";
        $imagePreview_medium = "../assets/images/places/medium/";
    }
    //So tera file se for o modo menu
    $hasFile = $edit_menu;
    ?>
<div id="my_house_edit_container">

    <!-- TODO: O MANEL MANDOU POR UM TODO PARA VER OS BUTOES -->
 <?php initGoogleMaps(); ?>

    <section id="place_edit_form">
        <form>
            <?php if ($place != null) { ?>
                <input type="hidden" name="placeID" value=<?=$place['placeID']?>>
            <?php } ?>

            <?php if ($userID != null) { ?>
                <input type="hidden" name="userID" value=<?=$userID?>>
            <?php } ?>

            <fieldset>
                <legend>Description</legend>
                <label>Title: <input type="text" name="title" size="40" value="<?=htmlspecialchars($title)?>" required> </label>
            </fieldset>

            <fieldset>

                <legend>Pictures</legend>
                <section id="img-upload" class="row">
                    <!--First we render the preview Images THIS SECTION IS USED BY JS DOM-->
                    <div id="house_form_img_preview">
                    </div>

                    <!--then we render the local Images-->
                    <div id="house_form_img_local">
                        <?php
                            for ($i = 0; $i < $imagearray_lenght; $i++) {
                                //Clean up the string with the path
                                unset($str_aux);

                                //LOAD THE MEDIUM SIZE IMAGE
                                if ($i === 0) {
                                    $str_aux = $imagePreview_medium . $imagearray[0]['image'];
                                    ?>
                                <div class="img_edit_local_container">
                                    <i class="fas fa-times delete_image_local" data-hash="<?=$imagearray[$i]['image']?>"></i>
                                    <img class="edit_place_img_medium" src="<?=$str_aux?>">
                                </div>

                            <?php
                                    } else {
                                        $str_aux = $imagePreview_small . $imagearray[$i]['image'];
                                        ?>
                                <div class="img_edit_local_container">
                                    <i class="fas fa-times delete_image_local" data-hash="<?=$imagearray[$i]['image']?>"></i>
                                    <img class="edit_place_img_small" src="<?=$str_aux?>">
                                </div>
                        <?php }
                            }
                            ?>
                    </div>

                    <label class="button" for="imageFile_add_place">Select foto</label>
                    <input class="button" type="file" id="imageFile_add_place" accept="image/*" name="imagePlaceFile[]" multiple multiple data-hasFile=<?=$hasFile?>>
                </section>

                <section id="img-delete_place_add" class="row">

                </section>


                </article>

            </fieldset>

            <fieldset>
                <legend>Description</legend>
                <textarea name="description" rows="10" cols="100" required><?=htmlspecialchars($description)?></textarea>


            </fieldset>

            <fieldset>
                <legend>Location</legend>
                <article class="column edit-house-location">

                    <label>Address: <input  id="form_place_address" type="text" name="address" size="70" value="<?=$address?>"> </label>
                    <label for="location">Location:
                        <select id="location" name="location" required>
                            <?php foreach ($all_locations as $eachLocation) {
                                    $selected = $eachLocation['locationID'] == $location ? "selected" : "";
                                    $locationString = $eachLocation['country'] . ' - ' . $eachLocation['city'];?>
                                <option value=<?=$eachLocation['locationID'] ?> <?= $selected ?>><?=htmlspecialchars($locationString)?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <input id="form_place_GPS" type="text" name="gpsCoords" size="70" value="<?=$gpsCoords?>" readonly>
                </article>

            </fieldset>

            <fieldset>
                <legend>House Caracteristics</legend>
                <article class="column edit-house-caracteristics">
                    <label>Number of Rooms:<input type="number" name="numRooms" value="<?=htmlspecialchars($numRooms)?>"></label>
                    <label>Number of Bathrooms:<input type="number" name="numBathrooms" value="<?=htmlspecialchars($numBathrooms)?>"></label>
                    <label>Capacity:<input type="number" name="capacity" value="<?=htmlspecialchars($capacity)?>"></label>
                </article>
            </fieldset>

            <p id="place-form-error" class="error-message"></p>

            <div id="edit_place_submit">
                <input class="button" type="submit" value="Confirm">
            </div>
        </form>
    </section>
</div>
<?php } ?>