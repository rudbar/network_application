<?php 
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
$error_array = ""; //Массив для сообщений об ошибках

if(isset($_POST['register_button'])){

    //Получение значений из формы

    //Имя
    $fname = strip_tags($_POST['reg_fname']); //Удаляет теги html
    $fname = str_replace(' ', '', $fname); //удаляет лишние пробелы
    $fname = ucfirst(strtolower($fname)); //делает первую букву заглавной

    //Фамилия
    $lname = strip_tags($_POST['reg_lname']); //Удаляет теги html
    $lname = str_replace(' ', '', $lname); //удаляет лишние пробелы
    $lname = ucfirst(strtolower($lname)); //делает первую букву заглавной

    //email
    $em = strip_tags($_POST['reg_email']); //Удаляет теги html
    $em = str_replace(' ', '', $em); //удаляет лишние пробелы
    $em = ucfirst(strtolower($em)); //делает первую букву заглавной

    //email 2
    $em2 = strip_tags($_POST['reg_email2']); //Удаляет теги html
    $em2 = str_replace(' ', '', $em2); //удаляет лишние пробелы
    $em2 = ucfirst(strtolower($em2)); //делает первую букву заглавной

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
                echo "Данный почтовый адрес уже используется";
            }
            


        }
        else {
            echo "Неправильный формат";
        }
    }
    else {
        echo "Почтовые адресы не совпадают";
    }


    if(strlen($fname) > 25 || strlen($fname) < 2) {
        echo "Ваше имя должно содержать от 2 до 25 символов";
    }
    
    if(strlen($lname) > 25 || strlen($lname) < 2) {
        echo "Ваша фамилия должна содержать от 2 до 25 символов";
    }

    if($password != $password2) {
        echo "Ваши пароли не совпадают";
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
            echo "Ваш пароль может содеражть только буквы латинского алфавита или цифцры";
        }
    }

    if(strlen($password > 30 || strlen($password) < 5)) {
        echo "Ваш пароль должен содержать от 5 до 30 символов";
    }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome here!</title>
</head>
<body>
    
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="Имя" required>
        <br>
        <input type="text" name="reg_lname" placeholder="Фамилия" required>
        <br>
        <input type="email" name="reg_email" placeholder="Email" required>
        <br>
        <input type="email" name="reg_email2" placeholder="Подтвердите Email" required>
        <br>
        <input type="password" name="reg_password" placeholder="Пароль" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Подтвердите пароль" required>
        <br>
        <input type="submit" name="register_button" value="Зарегестрироваться"> 


    </form>



</body>
</html>