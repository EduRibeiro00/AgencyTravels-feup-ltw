<?php
    // session_set_cookie_params(0, '/', , true, true);
    session_start();
    //init set
    // session.cookie secure

    session_regenerate_id(true);

    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generate_random_token();
    }

    function generate_random_token() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
?>