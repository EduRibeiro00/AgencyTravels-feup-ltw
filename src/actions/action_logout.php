<?php
    include_once('../includes/session_include.php');

    session_destroy();
    session_start();

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>