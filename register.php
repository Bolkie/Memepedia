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
                <article class="register">
                    <section class="subpageTitle">Rejestracja</section>
                    <hr class="divideLine">
                    <form action="includes/register-inc.php" method="post">
                        <section class="formFirstElement">
                            Nazwa użytkownika:<br>
                            <input type="text" name="username" id="">
                            <div class="registerError">
                                <?php
                                    if (isset($_SESSION['usernameRegisterError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['usernameRegisterError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            Email:<br>
                            <input type="email" name="email" id="">
                            <div class="registerError">
                                <?php
                                    if (isset($_SESSION['emailRegisterError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['emailRegisterError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            Hasło:<br>
                            <input type="password" name="password" id="">
                            <div class="registerError">
                                <?php
                                    if (isset($_SESSION['passwordRegisterError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['passwordRegisterError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            Powtórz hasło:<br>
                            <input type="password" name="passwordRepeat" id="">
                            <div class="registerError">
                                <?php
                                    if (isset($_SESSION['passwordRepeatRegisterError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['passwordRepeatRegisterError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            <br><button type="submit" name="submit">Zarejestruj się</button>
                        </section>
                        <section class="formLastElement">
                            <a class="reference" href="login.php">Posiadasz już konto? Kliknij tutaj.</a>
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
    unset($_SESSION['usernameRegisterError']);
    unset($_SESSION['emailRegisterError']);
    unset($_SESSION['passwordRegisterError']);
    unset($_SESSION['passwordRepeatRegisterError']);
?>