<?php
    session_start();

    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit();
    }

    require_once 'includes/dbh-inc.php';
    require_once 'includes/functions-inc.php';
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
                <?php 
                    printSinglePost($conn, $_GET['id']);
                ?>
                    
                <div class="commentLabel">Komentarze</div>

                <?php
                    if (isset($_SESSION['userId'])) {
                        echo '<article class="commentForm">';
                            echo '<form action="includes/newcomment-inc.php?id='.$_GET['id'].'" method="post">';
                                echo '<section class="formFirstElement">';
                                    echo '<textarea name="comment" id="" cols="30" rows="10" class="commentTextArea"></textarea>';
                                    echo '<div class="commentError">';
                                        if (isset($_SESSION['commentError'])) {
                                            echo '<i class="fas fa-exclamation-triangle"></i> '.$_SESSION['commentError'];
                                        } else {
                                            echo '<br>';
                                        }
                                    echo '</div>';
                                echo '</section>';
                                echo '<section class="formLastElement">';
                                    echo '<button type="submit" name="submit" class="commentSubmit">Wyślij</button>';
                                echo '</section>';
                            echo '</form>';
                        echo '</article>';
                    }
                    
                    printComments($conn, $_GET['id']);
                ?>
            </main>
        </div>

        <?php
            include_once 'footer.php';
        ?>
    </body>
</html>
<?php
    unset($_SESSION['commentError']);
?>