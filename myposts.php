<?php
    session_start();
    
    require_once 'includes/dbh-inc.php';
    require_once 'includes/functions-inc.php';

    if (!isset($_SESSION['userId'])) {
        header('Location: index.php');
        exit();
    }

    if (isset($_GET['page']) && ($_GET['page'] > $_SESSION['numberOfPages'] || $_GET['page'] < 1)) {
        header('Location: myposts.php');
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
                <?php
                    printPostsByUserId($conn, $_SESSION['userId']);
                ?>
            </main>

            <nav class="pageNav">
                <?php
                    printPageNavigation('myposts.php');
                ?>
            </nav>
        </div>

        <?php
            include_once 'footer.php';
        ?>
    </body>
</html>