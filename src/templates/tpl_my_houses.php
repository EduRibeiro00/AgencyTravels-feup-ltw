<?php
include_once('../templates/tpl_list_houses.php');
include_once('../database/db_user.php');
include_once('../database/db_places.php');


function draw_my_houses_base_head()
{ ?>
    <div id="my_houses_container">
    <?php } ?>

    <?php
    function draw_my_houses_statistics($userID)
    { 
        $userID=$_SESSION['userID'];    
    ?>

        <article id="my_houses_statistics_container">

            <header id="my_houses_statistics_header">
                <p>My Houses</p>
            </header>

            <section id="my_houses_statistics_info">
                <img class="circular-img" id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
                <p id="my_houses_statistics_owner"><?= getUserInformation($userID)['name']; ?></p>
                <i class="fas fa-chart-line"></i>
                <p>Number of Reservations: <?= getUserNumberofReservations($userID)['cnt']; ?></p>

            </section>

        </article>

        <aside id="my_houses_add_new">
            
            <div class="button">
                <a href="my_house_add.php?userID=<?=$userID?>">
                    
                    <i class="fas fa-plus"></i>
                    Add new Place
                </a>
            </div>

        </aside>

    <?php } ?>


    <?php

    function draw_my_houses_item_list($userID)
    {
        $array_places = getUserPlaces($userID);
        ?>

        <section id="my_houses_list_container">
            <?php
                list_houses_result($array_places,true);
            ?>

        </section>
    <?php }

    function draw_my_houses_base_end()
    { ?>
    </div>
<?php } 



function draw_form($place=null,$edit_menu=false)
{ 
    $userID=$_SESSION['userID'];

    if($edit_menu==true){
        $city=getPlace($place['placeID']);
        
        $title=$place['title'];  
        $address=$place['address'];
        $city_str=$city['city'];
        $country=$city['country'];
        $numRooms=$place['numRooms'];
        $numBathrooms=$place['numBathrooms'];
        $capacity=$place['capacity'];
    }else{
        $title='' ;
        $address='';
        $city_str='';
        $country='';
        $numRooms='';
        $numBathrooms='';
        $capacity='';
    }


    
?>

    <section id="place_edit_form">

        <form>
           
        <?php if($place != null) { ?>
                <input type="hidden" name="placeID" value=<?=$place['placeID']?>>
            <?php } ?>

        <?php if($userID != null) { ?>
                <input type="hidden" name="userID" value=<?=$userID?>>
            <?php } ?>

            <fieldset>
                <legend>Description</legend>
                <label>Title: <input type="text" name="title" size="40" value="<?= $title ?>"> </label>
            </fieldset>

            <fieldset>
            
            <legend>Pictures</legend>
                
            <!--Vai levar AJAX para apagar as photos-->
            <!--Vai levar AJAX para caregar as novas photos uploaded-->
            <section id="img-upload" class="row">
                <div>
                    <img src="http://tap1.fkimg.com/media/vr-splice-j/05/a8/a5/30.jpg">
                </div>
                <div>
                    
                </div>
                <label class="button" for="imageFile">Select foto</label>
                <input class="button" type="file" id="imageFile" accept="image/*" name="imageFile" data-hasFile="">
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
                    <label>City: <input type="text" name="city" value="<?=$city_str?>"> </label>
                    <label>Country: <input type="text" name="country" value="<?=$country?>"> </label>
                </article>


            </fieldset>

            <fieldset>
                <legend>House Caracteristics</legend>
                <article class="column edit-house-caracteristics">
                    <label>Number of Rooms:<input type="text" name="numRooms" value=" <?=$numRooms?>"></label>
                    <label>Number of Bathrooms:<input type="text" name="numBathrooms" value=" <?= $numBathrooms ?>"></label>
                    <label>Capacity:<input type="text" name="capacity" value=" <?= $capacity ?>"></label>
                </article>
            </fieldset>

            <div id="edit_place_submit">
                <input class="button" type="submit" value="Confirm">
            </div>

        </form>


    </section>

<?php  } ?>