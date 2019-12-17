<?php
    include_once('../includes/session_include.php');

    session_destroy();

    session_set_cookie_params(0, '/', true, true);
    session_start();
    // session_regenerate_id(true);
    
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generate_random_token();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>