<?php
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_form.php');
    include_once('../database/db_user.php');

    $userID = $_GET['userID'];

    $user_info = getUserInformation($userID);

    /* TODO: implementar seguranca e verificacao de sessoes. Por exemplo, o ID passado por GET tem de ser igual ao userID 
             armazenado na sessao, senao da die() (mesma coisa se n tiver sessao iniciada)
    */

    draw_head();
    draw_navbar();
    draw_profile_form('Edit profile', '../actions/action_edit_profile.php', $user_info);
    draw_footer();
?>