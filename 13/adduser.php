<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Новый пользователь</title>
</head>

<body>
<form action="adduser2.php" method="POST">
    <label> Логин
        <br /> <input type="text" name="login" />
    </label><br /><br />

    <label> Пароль
        <br /> <input type="password" name="password" />
    </label><br /><br />

    <label> Подтверждение пароля
        <br /> <input type="password" name="password2" />
    </label><br /><br />

    <input type="checkbox" name="admin" value='1'>Права администратора
    <br>
    <br><br />

    <input type="submit" value="Отправить" />
</form>
</body>
</html>