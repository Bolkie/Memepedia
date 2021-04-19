<nav class="mainNav">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <?php
        if (!isset($_SESSION['userId'])) {
            echo '<a href="login.php"><i class="fas fa-sign-in-alt"></i></a>';
        }

        if (isset($_SESSION['userId'])) {
            echo '<a href="myposts.php"><i class="fas fa-user"></i></a>';
            echo '<a href="newpost.php"><i class="fas fa-folder-plus"></i></a>'; 
            echo '<a href="includes/logout-inc.php"><i class="fas fa-sign-out-alt"></i></a>';
        }
    ?>
</nav>