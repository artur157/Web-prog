<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
</html> 
<?php

//Задача 10. Реализовать вход администратора с использованием
 //HTTP-авторизации для просмотра и удаления результатов.
 // Пример HTTP-аутентификации.
 // PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
 // Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.

function check_credentials($login, $password){   // проверяем пользователей
    global $admin_state;
    $auth_success = false;

    foreach (glob("users/*.xml") as $filename) {    // glob находит файловые пути, совпадающие с шаблоном
        $xml = simplexml_load_file($filename);

        foreach ($xml as $key => $value){
            if ($key == "login") $cur_login = $value;
            if ($key == "password") $cur_password = $value;
            if ($key == "admin") $cur_admin_state = $value;
        }

        if ($cur_login == $login && $cur_password == $password) {
            $auth_success = true;
            $admin_state = $cur_admin_state;
        }
    }

    return $auth_success;
}

header('Content-Type: text/html; charset=utf-8');
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    !check_credentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
    {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Требуется авторизация</h1>');
        exit();
    }


print('Вы успешно авторизовались и видите защищенные паролем данные.<br><br>');


// Здесь нужно прочитать список файлов в каталоге xml, например функцией glob(),
// открыть файлы в цикле, например с помощью simplexml_load_file(),
// вывести данные в таблицу с помощью print().
// Примеры работы с SimpleXMLElement можно найти в документации на сайте php.net
// или в файле php_manual_ru.chm. Удалить файл можно функцией unlink().

echo "<table><tr><td>Имя</td><td>E-mail</td><td>Год рождения</td><td>Пол</td><td>Кол-во конечностей</td><td>Биография</td><td>Сверхспособности</td>";
if (!empty($admin_state)) echo "<td>Файл</td><td>Удалить файл</td>";
echo "</tr>";

foreach (glob("xml/*.xml") as $filename){    // glob находит файловые пути, совпадающие с шаблоном
    echo "<tr>";
    $xml = simplexml_load_file($filename);

    foreach ($xml as $key => $value) {
        if($key != 'super') echo "<td>$value</td>";
        else {
            echo "<td>";
            $xml2 = $value;
            foreach ($xml2 as $key2 => $value2)
                echo $key2 . " ";
            echo "</td>";
        }
    }

    if (!empty($admin_state)) {
        echo "<td>$filename</td>";
        echo '<td><input type="button" name="button" value="Удалить файл" onClick="window.open(\'del.php?id=' . $filename . '\')"/></td></tr>';
    }
}

echo "</table>";

?>