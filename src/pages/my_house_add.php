<?php
include_once('../includes/session_include.php');
include_once('../database/db_user.php');
include_once('../templates/tpl_common.php');
include_once('../templates/tpl_my_houses.php');

include_once('../database/db_location.php');

if (isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
    $user_info = getUserInformation($_SESSION['userID']);
    $jsFiles = ['../js/main.js','../js/place_add.js','../js/place_form.js'];
} else {
    die(header('Location: ../pages/initial_page.php'));
}


$userID=$_SESSION['userID'];


draw_head($jsFiles);
draw_navbar($user_info, false);

?>
<div id="my_house_edit_container">

    <?php draw_form(null,false); ?>

</div>

<?php
draw_footer();
?>