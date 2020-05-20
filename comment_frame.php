<?php 
    require 'config/config.php';
    include("includes/classes/User.php");
    include("includes/classes/Post.php");


    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        $user_details_queyr = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_queyr);
    }
    else {
        header("Location: register.php");
    }

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

    <style type="text/css">
    * {
        font-size: 12px;
        font-family: Arial, Helvetica, Sans-serif;
    }

    </style>

    
    <script>
        function toggle() {
            var element = document.getElementById("comment_section");

            if(element.style.display == "block")
                element.style.display = "none";
            else
                element.style.display = "block";
        }
    </script>
    
    <?php 
    //id поста
    if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

    $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
    $row = mysqli_fetch_array($user_query);

    $posted_to = $row['added_by'];

    if(isset($_POST['postComment' . $post_id])) {
        $post_body = $_POST['post_body'];
        $post_body = mysqli_escape_string($con, $post_body);
        $date_time_now = date("Y-m-d H:i:s");
        $insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
        echo "<p>Комментарий отправлен!</p>";
    }
    ?>
    <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<? echo $post_id; ?>" method="POST">
        <textarea name="post_body"></textarea>
        <input type="submit" name="postComment<?php echo $post_id; ?>" value="Отправить">
    </form>

    <!-- Загрузка комментариев -->
    <?php 
    $get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
    $count = mysqli_num_rows($get_comments);

    if ($count != 0) {

        while($comment = mysqli_fetch_array($get_comments)) {

            $comment_body = $comment['post_body'];
            $posted_to = $comment['posted_to'];
            $posted_by = $comment['posted_by'];
            $date_added = $comment['date_added'];
            $removed = $comment['removed'];

            //Время
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_added); //Время поста
            $end_date = new DateTime($date_time_now); //Текущее время
            $interval = $start_date->diff($end_date); //Разница между датами 
            if($interval->y >= 1) {
                if($interval == 1)
                    $time_message = $interval->y . " year ago"; //1 год назад
                else 
                    $time_message = $interval->y . " years ago"; //1+ год назад
            }
            else if ($interval-> m >= 1) {
                if($interval->d == 0) {
                    $days = " назад";
                }
                else if($interval->d == 1) {
                    $days = $interval->d . " день назад";
                }
                else {
                    $days = $interval->d . " дней назад";
                }


                if($interval->m == 1) {
                    $time_message = $interval->m . " месяц". $days;
                }
                else {
                    $time_message = $interval->m . " месяца". $days;
                }

            }
            else if($interval->d >= 1) {
                if($interval->d == 1) {
                    $time_message = "Вчера";
                }
                else {
                    $time_message = $interval->d . " дней назад";
                }
            }
            else if($interval->h >= 1) {
                if($interval->h == 1) {
                    $time_message = $interval->h . " час назад";
                }
                else {
                    $time_message = $interval->h . " часов назад";
                }
            }
            else if($interval->i >= 1) {
                if($interval->i == 1) {
                    $time_message = $interval->i . " минуту назад";
                }
                else {
                    $time_message = $interval->i . " минут назад";
                }
            }
            else {
                if($interval->s < 30) {
                    $time_message = "Только что";
                }
                else {
                    $time_message = $interval->s . " секунд назад";
                }
            }

            $user_obj = new User($con, $posted_by);


            ?>
            <div class="comment_section">
                <a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
                <a href="<?php echo $posted_by?>" target="_parent"> <b> <?php echo $user_obj->getFirstAndLastName(); ?> </b></a>
                &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body; ?>
                <hr>
            </div>
            <?php
        }
    }
    else {
        echo "<center><br><br>Нет комментариев!</center>";
    }
    
    ?>





    
</body>
</html>