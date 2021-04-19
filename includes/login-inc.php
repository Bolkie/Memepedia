<?php
    session_start();

    if (!isset($_POST['submit'])) {
        header("Location: ../login.php");
        exit();
    }

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    logInUser($conn, $username, $password);
?>