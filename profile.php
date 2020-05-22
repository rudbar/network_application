<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");


if(isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

if(isset($_POST['remove_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
}

if(isset($_POST['add_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
}
if(isset($_POST['respond_request'])) {
    header("Location: requests.php");
}

?>

    <style type="text/css">
        .wrapper {
            margin-left: 0px;
            padding-left: 0px;
        }

    </style>
    
    <div class="profile_left">
        <img src="<?php echo $user_array['profile_pic']; ?>">
        
        <div class="profile_info">
            <p><?php echo "Посты: " . $user_array['num_posts']; ?></p>
            <p><?php echo "Отметки нравится: " . $user_array['num_likes']; ?></p>
            <p><?php echo "Друзья: " . $num_friends; ?></p>
        </div>

        <form action="<?php echo $username; ?>" method="POST">
            <?php 
            $profile_user_obj = new User($con, $username); 
            if($profile_user_obj->isClosed()) {
                header("Location: user_closed.php");
            }

            $logged_in_user_obj = new User($con, $userLoggedIn);

            if($userLoggedIn != $username) {

                if($logged_in_user_obj->isFriend($username)) {
                    echo '<input type="submit" name="remove_friend" class="danger" value="Удалить из друзей"><br>';
                }
                else if ($logged_in_user_obj->didReceiveRequest($username)) {
                    echo '<input type="submit" name="respond_request" class="warning" value="Ответить на запрос"><br>';
                }
                else if ($logged_in_user_obj->didSendRequest($username)) {
                    echo '<input type="submit" name="" class="default" value="Запрос отправлен"><br>';
                }
                else
                    echo '<input type="submit" name="add_friend" class="success" value="Добавить в друзья"><br>';

            }
            
            ?>


        </form>
    
    </div>


    <div class="main_column column">
        <?php echo $username; ?>


    </div>





    </div>
</body>
</html>