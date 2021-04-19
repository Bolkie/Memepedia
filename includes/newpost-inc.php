<?php
    session_start();

    if (!isset($_SESSION['userId'])) {
        header('Location: ../login.php');
        exit();
    }

    if (!isset($_POST['submit'])) {
        header('Location: ../newpost.php');
        exit();
    }

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    $title = $_POST['title'];
    $userId = $_SESSION['userId'];
    $creationDate = date('Y-m-d H:i:s');
    $file = $_FILES['file'];

    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTempName = $file['tmp_name'];
    $fileName = strtolower(str_replace(' ', '-', $fileName));

    if (incorrectTitle($title)) {
        $_SESSION['titleNewPostError'] = 'Tytuł postu jest niepoprawny, musi on zawierać od 4 do 64 liter/cyfr';
    }

    if (emptyFile($file)) {
        $_SESSION['fileNewPostError'] = 'Brak dodanego pliku';
    } else if (incorrectFile($fileName)) {
        $_SESSION['fileNewPostError'] = 'Dodany plik ma niepoprawne rozszerzenie';
    } else if (fileToLarge($fileSize)) {
        $_SESSION['fileNewPostError'] = 'Maksymalny rozmiar pliku to 20MB';
    }

    if (incorrectTitle($title) || emptyFile($file) || incorrectFile($fileName) || fileToLarge($fileSize)) {
        header('Location: ../newpost.php');
        exit();
    }

    $fileExtTmp = explode('.', $fileName);
    $fileExt = strtolower(end($fileExtTmp));

    $imageName = uniqid($userId.'-img-', true).'.'.$fileExt;
    $fileDestination = 'img/gallery/'.$imageName;

    move_uploaded_file($fileTempName, '../'.$fileDestination);
    addNewPost($conn, $title, $userId, $creationDate, $fileDestination);
?>