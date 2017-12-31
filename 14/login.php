<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Вход</title>
</head>

<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и имя соответствующего XML-файла.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
    // Делаем перенаправление на форму.
    header('Location: index.php');
}

// выводим если неправильной логин и пароль
if ($_GET['message'] == 1) {
    print('<div id="messages">');
    print 'Неверный логин и пароль';
    print('</div>');
}


// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="login.php" method="post" class="mini">
    <h1>Вход</h1>
    <label>Введите логин
        <br><input type="text" name="login" />
    </label><br><br>
    <label>Введите пароль
        <br><input type="password" name="pass" />
    </label><br><br>
    <input type="submit" class="mybutton" value="Войти" /><br><br>
    <a href='form.php' style="{text-align: center;}">Регистрация</a>
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
    foreach (glob ('xml/*.xml') as $filename) {
        $xml = simplexml_load_file("$filename");
        $login = $xml->login;
        $pass = $xml->pass;
        if ($login == $_POST['login'] && $pass == md5($_POST['pass'])){
            // Если все ок, то авторизуем пользователя.
            $_SESSION['login'] = $_POST['login'];
            // Записываем имя найденного XML-файла.
            $_SESSION['filename'] = $filename;
            header('Location: index.php');
        }

    }
    //echo 'Неверный логин и пароль';
    header(sprintf('Location: %s?message=1', $_SERVER['PHP_SELF']));
}
?>