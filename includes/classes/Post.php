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

    public function loadPostsFriends() {
        $str = ""; //возвращаемая строка
        $data = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

        while($row = mysqli_fetch_array($data)) {
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];

            //для user_to
            if($row['user_to'] == "none") {
                $user_to = "";
            }
            else {
                $user_to_obj = new User($con, $row['user_to']);
                $user_to_name = $user_to_obj->getFirstAndLastName();
                $user_to = "для <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
            }

            //Проверка закрыт ли аккаунт пользователя, кто оставил пост
            $added_by_obj = new User($this->con, $added_by);
            if($added_by_obj->isClosed()) {
                continue;
            }

            $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
            $user_row = mysqli_fetch_array($user_details_query);
            $first_name = $user_row['first_name'];
            $last_name = $user_row['last_name'];
            $profile_pic = $user_row['profile_pic'];

            //Время
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_time); //Время поста
            $end_date = new DateTime($date_time_now); //Текущее время
            $interval = $start_date->diff($end_date); // Разница между датами
            if($interval->y >= 1) {
                if($interval == 1)
                    $time_message = $interval->y . " год назад"; // 1 год назад
                else
                    $time_message = $interval->y . " года назад"; // 1+ года назад
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
                    $time_message = $interval->h . " часа назад";
                }
            }
            else if($interval->i >= 1) {
                if($interval->i == 1) {
                    $time_message = $interval->i . " минуту назад";
                }
                else {
                    $time_message = $interval->i . " минуты назад";
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

            $str .= "<div class='status_post'>
                        <div class='post_profile_pic'>
                            <img src='$profile_pic' width='50px'>
                        </div>

                        <div class='posted_by' style='color:#ACACAC;'>
                            <a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                        </div>
                        <div id='post_body'>
                            $body
                            <br>
                        </div>

                    </div>";



        }

        echo $str;


    }





}

?>