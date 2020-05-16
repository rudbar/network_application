<?php 
class Post {
    private $user_obj;
    private $con;

    public function __construct($con, $user){
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($body, $user_to) {
        $body = strip_tags($body); //удаляет html теги
        $body = mysqli_real_escape_string($this->con, $body);

        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);

        $check_empty = preg_replace('/\s+/', '', $body); //Удаляет все пробелы

        if($check_empty != "") {


            //Текущая дата и время
            $date_added = date("Y-m-d H:i:s");
            //Имя пользователя
            $added_by = $this->user_obj->getUsername();

            //Если пользователь на своем профиле, user_to будет 'none'
            if($user_to == $added_by) {
                $user_to = "none";
            }

            //отправляем пост
            $query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
            $returned_id = mysqli_insert_id($this->con);

            //Уведомление о вставке

            //Обновление счетчика постов для пользователя
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

        }
    }


}

?>