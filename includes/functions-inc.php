<?php
    // Login
    function logInUser($conn, $email, $password) {
        $existingAccount = emailAlreadyExists($conn, $email);

        if (!$existingAccount) {
            $_SESSION['loginError'] = 'Dane logowania się nie zgadzają';
            header('Location: ../login.php');
            exit();
        }

        $passwordHashed = $existingAccount['password'];
        $checkPassword = password_verify($password, $passwordHashed);

        if ($checkPassword == false) {
            $_SESSION['loginError'] = 'Dane logowania się nie zgadzają';
            header('Location: ../login.php');
            exit();
        }

        session_start();

        $_SESSION['userId'] = $existingAccount['user_id'];
        $_SESSION['userUsername'] = $existingAccount['username'];
        $_SESSION['userEmail'] = $existingAccount['email'];

        header('Location: ../index.php');
        exit();
    }

    // Register
    function incorrectUsername($username) {
        $result = true;

        if (preg_match('/^[a-zA-Z0-9]{4,64}$/', $username)) {
            $result = false;
        }

        return $result;
    }

    function incorrectEmail($email) {
        $result = true;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        }

        return $result;
    }

    function incorrectPassword($password) {
        $result = true;

        if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9!@#$%^&*-_=+.?]{8,64}$/', $password)) {
            $result = false;
        }

        return $result;
    }

    function passwordsDontMatch($password, $passwordRepeat) {
        $result = true;

        if ($password == $passwordRepeat) {
            $result = false;
        }

        return $result;
    }

    function usernameAlreadyExists($conn, $username) {
        $sql = "SELECT * FROM users WHERE username = ?;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row;
        } else {
            $result = false;

            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function emailAlreadyExists($conn, $email) {
        $sql = "SELECT * FROM users WHERE email = ?;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row;
        } else {
            $result = false;

            return $result;
        }

        mysqli_stmt_close($stmt);
    }
    
    function createNewUser($conn, $username, $email, $password) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashedPassword);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['accountCreated'] = true;
        header('Location: ../welcome.php');
        exit();
    }

    // New post
    function incorrectTitle($title) {
        $result = true;

        if (preg_match('/^[a-zA-Z0-9 _?!]{4,64}$/', $title)) {
            $result = false;
        }

        return $result;
    }

    function emptyFile($file) {
        $result = true;

        if (!empty($file)) {
            $result = false;
        }

        return $result;
    }

    function incorrectFile($fileName) {
        $result = true;

        $fileExtTmp = explode('.', $fileName);
        $fileExt = strtolower(end($fileExtTmp));
        $allowedExt = array('png', 'jpg', 'jpeg');

        if (in_array($fileExt, $allowedExt)) {
            $result = false;
        }

        return $result;
    }

    function fileToLarge($fileSize) {
        $result = true;

        if ($fileSize < 20971520) {
            $result = false;
        }

        return $result;
    }

    function addNewPost($conn, $title, $userId, $creationDate, $imageName) {
        $sql = "INSERT INTO posts (title, user_id, creation_date, image_name) VALUES (?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $title, $userId, $creationDate, $imageName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: ../myposts.php');
        exit();
    }

    // Posts
    function printPageNavigation($pageUrl) {
        isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
        isset($_SESSION['numberOfPages']) ? $numberOfPages = $_SESSION['numberOfPages'] : $numberOfPages = 1;

        $firstPage = 1;
        $page > 1 ? $previousPage = $page - 1 : $previousPage = 1;
        $currentPage = $page;
        $page < $numberOfPages ? $nextPage = $page + 1 : $nextPage = $numberOfPages;
        $numberOfPages == 0 ? $nextPage = 1 : $nextPage = $numberOfPages;
        $numberOfPages == 0 ? $lastPage = 1 : $lastPage = $numberOfPages;

        echo '<a href="'.$pageUrl.'?page='.$firstPage.'"><i class="fas fa-less-than-equal"></i></a>';
        echo '<a href="'.$pageUrl.'?page='.$previousPage.'"><i class="fas fa-less-than"></i></a>';
        echo ' '.$currentPage.' ';
        echo '<a href="'.$pageUrl.'?page='.$nextPage.'"><i class="fas fa-greater-than"></i></a>';
        echo '<a href="'.$pageUrl.'?page='.$lastPage.'"><i class="fas fa-greater-than-equal"></i></a>';
    }

    function printPostData($conn, $postId, $title, $userId, $username, $creationDate, $imageName, $voteCount) {
        echo '<article class="post">';
            echo '<section class="postTitle"><a href="post.php?id='.$postId.'">'.$title.'</a></section>';
            echo '<section class="postAuthor"><b>'.$username.'</b>, '.$creationDate.'</section>';
            echo '<section class="postContent">'.'<img src="'.$imageName.'" alt="error">'.'</section>';
            if (isset($_SESSION['userId'])) {
                if (getPostVoteStatus($conn, $postId, $_SESSION['userId']) == 1) {
                    echo '<section class="postPoints"><a href="includes/downvote-inc.php?id='.$postId.'"><i class="far fa-minus-square"></i></a> '.$voteCount.' <a href="includes/upvote-inc.php?id='.$postId.'"><i class="far fa-plus-square" id="upvote"></i></a></section>';
                } else if (getPostVoteStatus($conn, $postId, $_SESSION['userId']) == -1) {
                    echo '<section class="postPoints"><a href="includes/downvote-inc.php?id='.$postId.'"><i class="far fa-minus-square" id="downvote"></i></a> '.$voteCount.' <a href="includes/upvote-inc.php?id='.$postId.'"><i class="far fa-plus-square"></i></a></section>';
                } else {
                    echo '<section class="postPoints"><a href="includes/downvote-inc.php?id='.$postId.'"><i class="far fa-minus-square"></i></a> '.$voteCount.' <a href="includes/upvote-inc.php?id='.$postId.'"><i class="far fa-plus-square"></i></a></section>';
                }
            } else {
                echo '<section class="postPoints"><a href="includes/downvote-inc.php?id='.$postId.'"><i class="far fa-minus-square"></i></a> '.$voteCount.' <a href="includes/upvote-inc.php?id='.$postId.'"><i class="far fa-plus-square"></i></a></section>';
            }
        echo '</article>';
    }

    function printNoPostsMessage() {
        echo '<article class="post">';
            echo '<section class="noPosts">Brak postów do wyświetlenia</section>';
        echo '</article>';
    }

    function getPostVoteCount($conn, $postId) {
        $sql = "SELECT IFNULL(SUM(value), 0) AS total_votes FROM votes WHERE post_id = $postId;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $voteCount = $row['total_votes'];

        return $voteCount;
    }

    function getPostVoteStatus($conn, $postId, $userId) {
        $sql = "SELECT * FROM votes WHERE post_id = $postId AND user_id = $userId";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        $status = 0;

        if ($rowCount > 0) {
            $row = mysqli_fetch_assoc($result);
            $status = $row['value'];
        }

        return $status;
    }

    function printSinglePost($conn, $postId) {
        $sql = "SELECT p.title, p.user_id, p.creation_date, p.image_name, u.username FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.post_id = $postId;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount <= 0) {
            header('Location: index.php');
            exit();
        }

        $row = mysqli_fetch_assoc($result);

        $title = $row['title'];
        $userId = $row['user_id'];
        $username = $row['username'];
        $creationDate = date('d.m.Y H:i', strtotime($row['creation_date'])); 
        $imageName = $row['image_name'];
        $voteCount = getPostVoteCount($conn, $postId, $userId);

        printPostData($conn, $postId, $title, $userId, $username, $creationDate, $imageName, $voteCount);
    }

    function printAllPosts($conn) {
        $sql = "SELECT * FROM posts;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        $resultsPerPage = 10;
        $_SESSION['numberOfPages'] = ceil($rowCount / $resultsPerPage);

        if ($rowCount <= 0) {
            printNoPostsMessage();
        }

        $pageFirstResult = ($page - 1) * $resultsPerPage;
        $sql = "SELECT p.post_id, p.title, p.user_id, p.creation_date, p.image_name, u.username FROM posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.post_id DESC LIMIT $pageFirstResult, $resultsPerPage;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $postId = $row['post_id'];
            $title = $row['title'];
            $userId = $row['user_id'];
            $username = $row['username'];
            $creationDate = date('d.m.Y H:i', strtotime($row['creation_date'])); 
            $imageName = $row['image_name'];
            $voteCount = getPostVoteCount($conn, $postId, $userId);

            printPostData($conn, $postId, $title, $userId, $username, $creationDate, $imageName, $voteCount);
        }
    }

    function printPostsByUserId($conn, $userId) {
        $sql = "SELECT * FROM posts WHERE user_id = $userId;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        $resultsPerPage = 10;
        $_SESSION['numberOfPages'] = ceil($rowCount / $resultsPerPage);

        if ($rowCount <= 0) {
            printNoPostsMessage();
        }

        $pageFirstResult = ($page - 1) * $resultsPerPage;
        $sql = "SELECT p.post_id, p.title, p.user_id, p.creation_date, p.image_name, u.username FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = $userId ORDER BY p.post_id DESC LIMIT $pageFirstResult, $resultsPerPage;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $postId = $row['post_id'];
            $title = $row['title'];
            $userId = $row['user_id'];
            $username = $row['username'];
            $creationDate = date('d.m.Y H:i', strtotime($row['creation_date'])); 
            $imageName = $row['image_name'];
            $voteCount = getPostVoteCount($conn, $postId, $userId);

            printPostData($conn, $postId, $title, $userId, $username, $creationDate, $imageName, $voteCount);
        }
    }

    // Comments
    function incorrectComment($comment) {
        $result = true;

        if (strlen($comment > 0) && strlen($comment) <= 1000) {
            $result = false;
        }

        return $result;
    }

    function addNewComment($conn, $postId, $userId, $creationDate, $comment) {
        $sql = "INSERT INTO comments (post_id, user_id, creation_date, comment) VALUES (?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $postId, $userId, $creationDate, $comment);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['commentAdded'] = true;
        header('Location: ../post.php?id='.$postId);
        exit();
    }

    function printCommentData($username, $creationDate, $comment) {
        echo '<article class="comment">';
            echo '<section class="commentAuthor"><b>'.$username.'</b>, '.$creationDate.'</section>';
            echo '<section class="commentContent">'.$comment.'</section>';
        echo '</article>';
    }

    function printNoCommentsMessage() {
        echo '<article class="comment">';
            echo '<section class="noComments">Brak komentarzy do wyświetlenia</section>';
        echo '</article>';
    }

    function printComments($conn, $postId) {
        $sql = "SELECT c.post_id, c.user_id, c.creation_date, c.comment, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.post_id = $postId;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount <= 0) {
            printNoCommentsMessage();
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['username'];
            $creationDate = date('d.m.Y H:i', strtotime($row['creation_date'])); 
            $comment = $row['comment'];

            printCommentData($username, $creationDate, $comment);
        }
    }

    // Votes
    function alreadyVoted($conn, $postId, $userId) {
        $result = true;

        $sql = "SELECT * FROM votes WHERE post_id = $postId AND user_id = $userId;";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount <= 0) {
            $result = false;
        }

        return $result;
    }

    function ratePost($conn, $postId, $userId, $value) {
        $sql = "INSERT INTO votes (post_id, user_id, value) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $postId, $userId, $value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: ../post.php?id='.$postId);
        exit();
    }
?>