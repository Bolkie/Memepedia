<?php
    session_start();

    if (isset($_SESSION['userId'])) {
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pl">
    <?php
        include_once 'header.php';
    ?>

    <body>
        <?php
            include_once 'navigation.php';
        ?>
        
        <div class="container">
            <main>
                <article class="login">
                    <section class="subpageTitle">Logowanie</section>
                    <hr class="divideLine">
                    <form action="includes/login-inc.php" method="post">
                        <section class="formFirstElement">
                            Email:<br>
                            <input type="text" name="username" id="">
                            <div class="loginError">
                                <br>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            Hasło:<br>
                            <input type="password" name="password" id="">
                            <div class="loginError">
                                <?php
                                    if (isset($_SESSION['loginError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['loginError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            <br>
                            <button type="submit" name="submit">Zaloguj się</button>
                        </section>
                        <section class="formLastElement">
                            <a class="reference" href="register.php">Nie posiadasz jeszcze konta? Kliknij tutaj.</a>
                        </section>
                    </form>
                </article>
            </main>
        </div>

        <?php
            include_once 'footer.php';
        ?>
    </body>
</html>
<?php
    unset($_SESSION['loginError']);
?>