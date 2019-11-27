<?php
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_profile_form.php');

    /* TODO: implementar seguranca e verificacao de sessoes. Se tiver sessao iniciada, dar die()
    */

    draw_head();
    draw_navbar();
    draw_profile_form('Signup', '../actions/action_signup.php');
    draw_footer();
?>