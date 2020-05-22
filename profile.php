<?php 
include("includes/header.php");



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

        <input type="submit" class="success" data-toggle="modal" data-target="#post_form" value="Оставить запись">
    
    </div>


    <div class="main_column column">
        <?php echo $username; ?>

    

    </div>

    <!-- Modal -->
    <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Оставить запись</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <p>Данная запись появится на стене у пользователя и в его новостной ленте, которую смогут увидеть все друзья</p>

            <form class="profile_post" action="" method="POST">
                <div class="form-group">
                    <textarea class="form-control" name="post_body"></textarea>
                    <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                    <input type="hidden" name="user_to" value="<?php echo $$username; ?>">
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Отправить</button>
        </div>
        </div>
    </div>
    </div>



    </div>
</body>
</html>