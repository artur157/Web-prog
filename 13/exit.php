<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
unset($_SESSION['login']);
unset($_SESSION['filename']);
session_destroy();
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