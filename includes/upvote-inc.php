<?php
    session_start();

    if (!isset($_GET['id'])) {
        header('Location: ../index.php');
        exit();
    }

    if (!isset($_SESSION['userId'])) {
        header('Location: ../login.php');
        exit();
    }

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';
    
    $postId = $_GET['id'];
    $userId = $_SESSION['userId'];

    if (alreadyVoted($conn, $postId, $userId)) {
        header('Location: ../post.php?id='.$postId);
        exit();
    }

    ratePost($conn, $postId, $userId, 1);
?>