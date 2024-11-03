<?php
session_start();

if (isset($_POST['rememberme']) && $_POST['rememberme'] == 'false') {
   
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    session_destroy(); 
    header("Location: /tp/login-form-18/login-form-18/index.php");
    exit();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: /tp/login-form-18/login-form-18/index.php");
    exit();
}
?>
