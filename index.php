<?php
    session_start();

    require_once 'includes/dbh-inc.php';
    require_once 'includes/functions-inc.php';

    if (isset($_GET['page']) && ($_GET['page'] > $_SESSION['numberOfPages'] || $_GET['page'] < 1)) {
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
                <?php 
                    printAllPosts($conn);
                ?>
            </main>

            <nav class="pageNav">
                <?php
                    printPageNavigation('index.php');
                ?>
            </nav>
        </div>

        <?php
            include_once 'footer.php';
        ?>
    </body>
</html>