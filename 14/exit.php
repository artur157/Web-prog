<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
unset($_SESSION['login']);
unset($_SESSION['filename']);
session_destroy();
setcookie('name_value', '', 100000);
setcookie('email_value', '', 100000);
setcookie('age_value', '', 100000);
setcookie('sex_value', '', 100000);
setcookie('quantity_value', '', 100000);
setcookie('super_value', '', 100000);
setcookie('bio_value', '', 100000);
setcookie('contract_value', '', 100000);
?>

<html>
    <head>
        <title>Добро пожаловать</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        Вы успешно вышли из аккаунта.<br>
        <a href="login.php">Перейти к странице входа</a>
    </body>
</html>