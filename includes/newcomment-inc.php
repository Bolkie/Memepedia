<?php
    session_start();

    if (!isset($_POST['submit']) || !isset($_GET['id'])) {
        header('Location: ../post.php?id='.$_GET['id']);
        exit();
    }

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    $comment = $_POST['comment'];

    if (incorrectComment($comment) == true) {
        $_SESSION['commentError'] = 'Komentarz musi zawierać od 1 do 1000 znaków';
        header('Location: ../post.php?id='.$_GET['id']);
        exit();
    }

    $postId = $_GET['id'];
    $userId = $_SESSION['userId'];
    $creationDate = date('Y-m-d H:i:s'); 
    $comment = $_POST['comment'];

    addNewComment($conn, $postId, $userId, $creationDate, $comment);
?>