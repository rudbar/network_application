<?php 
session_start();
$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()) 
{
    echo "Failed to connect: " . mysqli_connect_errno();
}

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
    $fname = ucfirst(strtolower($fname)); //делает первую букву заглавной
    $_SESSION['reg_fname'] = $fname; //сохраняет имя в переменную текущей сессии

    //Фамилия
    $lname = strip_tags($_POST['reg_lname']); //Удаляет теги html
    $lname = str_replace(' ', '', $lname); //удаляет лишние пробелы
    $lname = ucfirst(strtolower($lname)); //делает первую букву заглавной
    $_SESSION['reg_lname'] = $lname; //сохраняет фамилию в переменную текущей сессии

    //email
    $em = strip_tags($_POST['reg_email']); //Удаляет теги html
    $em = str_replace(' ', '', $em); //удаляет лишние пробелы
    $em = ucfirst(strtolower($em)); //делает первую букву заглавной
    $_SESSION['reg_email'] = $em; //сохраняет email в переменную текущей сессии

    //email 2
    $em2 = strip_tags($_POST['reg_email2']); //Удаляет теги html
    $em2 = str_replace(' ', '', $em2); //удаляет лишние пробелы
    $em2 = ucfirst(strtolower($em2)); //делает первую букву заглавной
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

        //Генерация имени пользователя путем связки имени и фамилии
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        //если имя пользователя уже существует, добавляем номер к имени пользователя
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; // добавляем 1 к $i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }
    }

}


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome here!</title>
</head>
<body>
    
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="Имя" value="<?php 
        if(isset($_SESSION['reg_fname'])) {
            echo $_SESSION['reg_fname'];
        } 
        ?>" required>
        <br>
        <?php if(in_array("Ваше имя должно содержать от 2 до 25 символов<br>", $error_array)) echo "Ваше имя должно содержать от 2 до 25 символов<br>"; ?>
        


        <input type="text" name="reg_lname" placeholder="Фамилия" value="<?php 
        if(isset($_SESSION['reg_lname'])) {
            echo $_SESSION['reg_lname'];
        } 
        ?>" required>
        <br>
        <?php if(in_array("Ваша фамилия должна содержать от 2 до 25 символов<br>", $error_array)) echo "Ваша фамилия должна содержать от 2 до 25 символов<br>"; ?>


        <input type="email" name="reg_email" placeholder="Email" value="<?php 
        if(isset($_SESSION['reg_email'])) {
            echo $_SESSION['reg_email'];
        } 
        ?>" required>
        <br>

        <input type="email" name="reg_email2" placeholder="Подтвердите Email" value="<?php 
        if(isset($_SESSION['reg_email2'])) {
            echo $_SESSION['reg_email2'];
        } 
        ?>" required>
        <br>
        <?php if(in_array("Данный почтовый адрес уже используется<br>", $error_array)) echo "Данный почтовый адрес уже используется<br>";
        else if(in_array("Неправильный формат почтового адреса<br>", $error_array)) echo "Неправильный формат почтового адреса<br>";
        else if(in_array("Почтовые адресы не совпадают<br>", $error_array)) echo "Почтовые адресы не совпадают<br>"; ?>

        <input type="password" name="reg_password" placeholder="Пароль" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Подтвердите пароль" required>
        <br>
        <?php if(in_array("Ваши пароли не совпадают<br>", $error_array)) echo "Ваши пароли не совпадают<br>";
        else if(in_array("Ваш пароль может содеражть только буквы латинского алфавита или цифцры<br>", $error_array)) echo "Ваш пароль может содеражть только буквы латинского алфавита или цифцры<br>";
        else if(in_array("Ваш пароль должен содержать от 5 до 30 символов<br>", $error_array)) echo "Ваш пароль должен содержать от 5 до 30 символов<br>"; ?>


        <input type="submit" name="register_button" value="Зарегестрироваться"> 


    </form>



</body>
</html>