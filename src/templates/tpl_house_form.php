<?php 
include_once('../database/db_user.php');
include_once('../database/db_places.php');

function draw_form($place = null, $edit_menu = false) {
    $userID = $_SESSION['userID'];

    if ($edit_menu == true) {
        $city = getPlace($place['placeID']);

        $title = $place['title'];
        $address = $place['address'];
        $city_str = $city['city'];
        $country = $city['country'];
        $numRooms = $place['numRooms'];
        $numBathrooms = $place['numBathrooms'];
        $capacity = $place['capacity'];


        $imagearray = getPlaceImages($place['placeID']);
        $imagearray_lenght = count($imagearray);
        $imagePreview_small = "../assets/images/places/small/";
        $imagePreview_medium = "../assets/images/places/medium/";


        //GET ALL IMAGES


    } else {
        $title = '';
        $address = '';
        $city_str = '';
        $country = '';
        $numRooms = '';
        $numBathrooms = '';
        $capacity = '';
        $imagearray_lenght = 0;

        $imagePreview_small = "../assets/images/places/small/";
        $imagePreview_medium = "../assets/images/places/medium/";
    }
    //So tera file se for o modo menu
    $hasFile = $edit_menu

    ?>
<!-- TODO: O MANEL MANDOU POR UM TODO PARA VER OS BUTOES -->
    <section id="place_edit_form">

        <form>

            <?php if ($place != null) { ?>
                <input type="hidden" name="placeID" value=<?= $place['placeID'] ?>>
            <?php } ?>

            <?php if ($userID != null) { ?>
                <input type="hidden" name="userID" value=<?= $userID ?>>
            <?php } ?>

            <fieldset>
                <legend>Description</legend>
                <label>Title: <input type="text" name="title" size="40" value="<?= $title ?>"> </label>
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
                                    $str_aux = $imagePreview_medium.$imagearray[0]['image'];
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
                                <i class="fas fa-times delete_image_local"data-hash="<?=$imagearray[$i]['image']?>"></i>
                                <img class="edit_place_img_small" src="<?= $str_aux?>">
                            </div>
                            <?php } 
                            }
                            ?>
                    </div>
    
                    <label class="button" for="imageFile_add_place">Select foto</label>
                    <input class="button" type="file" id="imageFile_add_place" accept="image/*" name="imagePlaceFile[]" multiple multiple data-hasFile=<?= $hasFile ?>>
                </section>

                <section id="img-delete_place_add" class="row">

                </section>


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
                <article class="column edit-house-location">
                    <label>Address: <input type="text" name="address" size="70" value="<?= $address ?>"> </label>
                    <label>City: <input type="text" name="city" value="<?= $city_str ?>"> </label>
                    <label>Country: <input type="text" name="country" value="<?= $country ?>"> </label>
                </article>


            </fieldset>

            <fieldset>
                <legend>House Caracteristics</legend>
                <article class="column edit-house-caracteristics">
                    <label>Number of Rooms:<input type="text" name="numRooms" value=" <?= $numRooms ?>"></label>
                    <label>Number of Bathrooms:<input type="text" name="numBathrooms" value=" <?= $numBathrooms ?>"></label>
                    <label>Capacity:<input type="text" name="capacity" value=" <?= $capacity ?>"></label>
                </article>
            </fieldset>

            <div id="edit_place_submit">
                <input class="button" type="submit" value="Confirm">
            </div>

        </form>


    </section>
<?php } ?>