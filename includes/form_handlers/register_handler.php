<?php
mb_internal_encoding('UTF-8');
if (!function_exists("mb_ucfirst"))
{
    function mb_ucfirst($str)
    {
        return ucfirst(substr($str, 0, 1) . substr($str, 1));
    }
}
require 'config/config.php';

//Объявление переменных для предотвращения ошибок
$fname = ""; //Имя
$lname = ""; //Фамилия
$em = ""; //email
$em2 = ""; //email 2
$password = ""; //пароль
$password2 = ""; //пароль 2
$date = ""; //Дата регистрации
$error_array = array(); //Массив для сообщений об ошибках

if(isset($_POST['register_button'])){

    //Получение значений из формы

    //Имя
    $fname = strip_tags($_POST['reg_fname']); //Удаляет теги html
    $fname = str_replace(' ', '', $fname); //удаляет лишние пробелы
    $fname = mb_ucfirst(mb_strtolower($fname)); //делает первую букву заглавной
    $_SESSION['reg_fname'] = $fname; //сохраняет имя в переменную текущей сессии

    //Фамилия
    $lname = strip_tags($_POST['reg_lname']); //Удаляет теги html
    $lname = str_replace(' ', '', $lname); //удаляет лишние пробелы
    $lname = mb_ucfirst(mb_strtolower($lname)); //делает первую букву заглавной
    $_SESSION['reg_lname'] = $lname; //сохраняет фамилию в переменную текущей сессии

    //email
    $em = strip_tags($_POST['reg_email']); //Удаляет теги html
    $em = str_replace(' ', '', $em); //удаляет лишние пробелы
    $em = mb_ucfirst(mb_strtolower($em)); //делает первую букву заглавной
    $_SESSION['reg_email'] = $em; //сохраняет email в переменную текущей сессии

    //email 2
    $em2 = strip_tags($_POST['reg_email2']); //Удаляет теги html
    $em2 = str_replace(' ', '', $em2); //удаляет лишние пробелы
    $em2 = mb_ucfirst(mb_strtolower($em2)); //делает первую букву заглавной
    $_SESSION['reg_email2'] = $em2; //сохраняет email2 в переменную текущей сессии

    //Пароль
    $password = strip_tags($_POST['reg_password']); //Удаляет теги html
    $password2 = strip_tags($_POST['reg_password2']); //Удаляет теги html

    $date = date("Y-m-d"); //Текущая дата

    if($em == $em2) {
        //Проверка email на правильный формат
        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //Проверка на email на дубликат
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //Подсчет возвращенных строк
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0) {
                array_push($error_array, "Данный почтовый адрес уже используется<br>");
            }
            


        }
        else {
            array_push($error_array, "Неправильный формат почтового адреса<br>");
        }
    }
    else {
        array_push($error_array, "Почтовые адресы не совпадают<br>");
    }


    if(strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Ваше имя должно содержать от 2 до 25 символов<br>");
    }
    
    if(strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Ваша фамилия должна содержать от 2 до 25 символов<br>");
    }

    if($password != $password2) {
        array_push($error_array, "Ваши пароли не совпадают<br>");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Ваш пароль может содеражть только буквы латинского алфавита или цифцры<br>");
        }
    }

    if(strlen($password > 30 || strlen($password) < 5)) {
        array_push($error_array, "Ваш пароль должен содержать от 5 до 30 символов<br>");
    }

    if(empty($error_array)) {
        $password = md5($password); //зашифровка пароля перед отправков в базу данных
        //          ^ this is no longer secure ^^
        //Генерация имени пользователя путем связки имени и фамилии
        $username = mb_strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        //если имя пользователя уже существует, добавляем номер к имени пользователя
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; // добавляем 1 к $i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }

        //Присваивание картинки профиля для пользователя
        $rand = rand(1, 2); //случайное число между 1 и 2

        if($rand == 1)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        else if($rand == 2)
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        

        $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: #14C800;'>Все готово! Теперь можно войти!</span><br>");

        //Очистка переменных текущией сессии
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}
?>