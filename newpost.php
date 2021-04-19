<?php
    session_start();

    if (!isset($_SESSION['userId'])) {
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
                    <section class="subpageTitle">Nowy post</section>
                    <hr class="divideLine">
                    <form action="includes/newpost-inc.php" method="post" enctype="multipart/form-data">
                        <section class="formFirstElement">
                            Tytuł:<br>
                            <input type="text" name="title" id="">
                            <div class="newPostError">
                                <?php
                                    if (isset($_SESSION['titleNewPostError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['titleNewPostError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formMiddleElement">
                            Załącznik:<br>
                            <input type="file" name="file" accept=".png, .jpg, .jpeg">
                            <div class="newPostError">
                                <?php
                                    if (isset($_SESSION['fileNewPostError'])) {
                                        echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['fileNewPostError'];
                                    } else {
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                        </section>
                        <section class="formLastElement">
                            <button type="submit" name="submit">Opublikuj</button>
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
    unset($_SESSION['titleNewPostError']);
    unset($_SESSION['fileNewPostError']);
?>