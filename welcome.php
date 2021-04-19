<?php
    session_start();

    if (!isset($_SESSION['accountCreated'])) {
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
                <article class="welcome">
                    <section class="subpageTitle">Witaj</section>
                    <hr class="divideLine">
                    <section class="content">
                        Witamy na Memepedii. Dziękujemy za rejestrację na naszej stronie. Teraz możesz się zalogować na nowo utworzone konto przy pomocy danych, które podałeś podczas rejestracji. Aby przejść do ekranu logowania kliknij <a href="login.php">TUTAJ</a>.
                    </section>
                </article>
            </main>
        </div>

        <?php
            include_once 'footer.php';
        ?>
    </body>
</html>
<?php
    unset($_SESSION['accountCreated']);
?>