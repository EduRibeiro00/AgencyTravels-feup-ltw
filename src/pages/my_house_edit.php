<?php

    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_house_edit_form.php');

    draw_head(['../js/main.js']);
    draw_navbar();

    $placeId=$_GET['placeID'];

?>




<div id="my_house_edit_container">

    <h2>My House Edit</h2>
    
    <?php draw_form($placeId); ?>

</div>

<?php
    draw_footer();
?>