<?php 
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome here!</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>

    <?php 

    if(isset($_POST['register_button'])) {
        echo '
        <script>

        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });

        </script>
        
        ';
    }


    ?>

    <div class="wrapper">
        
        <div class="login_box">
        
            <div class="login_header">
                <h1>Название</h1>
                Войдите или зарегестрируйтесь ниже!
            </div>

            <div id="first">

                <form action="register.php" method="POST" accept-charset="UTF-8">
                    <input type="email" name="log_email" placeholder="Email Адрес" value="<?php 
                    if(isset($_SESSION['log_email'])) {
                        echo $_SESSION['log_email'];
                    } 
                    ?>" required>
                    <br>
                    <input type="password" name="log_password" placeholder="Пароль">
                    <br>
                    <?php if(in_array("Неправильный Email или пароль<br>", $error_array)) echo  "Неправильный Email или пароль<br>"; ?>
                    <input type="submit" name="login_button" value="Войти">
                    <br>
                    <a href="#" id="signup" class="signup">Нужен профиль? Зарегистрируйтесь здесь!</a>

                </form>

            </div>
            
            <div id="second">

                <form action="register.php" method="POST" accept-charset="UTF-8">
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
                    <br>

                    <?php if(in_array("<span style='color: #14C800;'>Все готово! Теперь можно войти!</span><br>", $error_array)) echo "<span style='color: #14C800;'>Все готово! Теперь можно войти!</span><br>"; ?>
                    <a href="#" id="signin" class="signin">Уже зарегестрированы? Войдите здесь!</a>

                </form>
            </div>

        </div>

    </div>



</body>
</html>