<?php
    include_once('../includes/session_include.php');

    session_destroy();

    ini_set('session.cookie_httponly', '1');
    // ini_set('session.cookie_secure', '1'); // este ini_set da cookie_secure encontra-se comentado, uma vez que
                                            // certas funcionalidades ligadas a sessao e a seus atributos
                                            // (como por exemplo o login) deixavam de funcionar.

    session_start();
    session_regenerate_id(true);
    
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generate_random_token();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>