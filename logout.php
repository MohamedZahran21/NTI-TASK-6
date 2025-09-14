<?php
session_start();

if (isset($_SESSION['auth_user'])) {
    unset($_SESSION['auth_user']);
    session_destroy();
}

header("Location: .");
die;
?>