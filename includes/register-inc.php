<?php
    session_start();

    if (!isset($_POST['submit'])) {
        header('Location: ../register.php');
        exit();
    }

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['passwordRepeat'];
    $noErrors = true;

    if (incorrectUsername($username) == true) {
        $_SESSION['usernameRegisterError'] = 'Nazwa użytkownika jest niepoprawna, musi on zawierać od 5 do 64 liter/cyfr';
        $noErrors = false;
    }

    if (incorrectEmail($email) == true) {
        $_SESSION['emailRegisterError'] = 'Email jest niepoprawny';
        $noErrors = false;
    }

    if (incorrectPassword($password) == true) {
        $_SESSION['passwordRegisterError'] = 'Hasło jest niepoprawne, musi ono zawierać od 8 do 64 liter/cyfr/symboli';
        $noErrors = false;
    }

    if (passwordsDontMatch($password, $passwordRepeat) == true) {
        $_SESSION['passwordRepeatRegisterError'] = 'Hasła nie zgadzają się ze sobą';
        $noErrors = false;
    }

    if (usernameAlreadyExists($conn, $username) == true) {
        $_SESSION['usernameRegisterError'] = 'Podana nazwa użytkownika jest już zajęta';
        $noErrors = false;
    }

    if (emailAlreadyExists($conn, $email) == true) {
        $_SESSION['emailRegisterError'] = 'Podany email jest już zajęty';
        $noErrors = false;
    }

    if ($noErrors == false) {
        header('Location: ../register.php');
        exit();
    }

    createNewUser($conn, $username, $email, $password);
?>