<?php
//ini_set('display_errors','On');
//phpinfo();
//exit;
//ini_set('display_errors','Off');

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Если есть параметр save, то выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.<br>';
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
    }

    if (!empty($_COOKIE['pass']))
        $messages[] = sprintf('Вы можете войти с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.',
            strip_tags($_COOKIE['login']),
            strip_tags($_COOKIE['pass']));

    // Складываем признак ошибок в массив.
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['contract'] = !empty($_COOKIE['contract_error']);

    // Выдаем сообщения об ошибках.
    if ($errors['name']) {
        if ($_COOKIE['name_error'] == '1') {
            $messages[] = '<div class="error">Заполните имя.</div>';
        }
        else {
            $messages[] = '<div class="error">Имя некорректно.</div>';
        }
        // куки не удаляем, ещё понадобится
    }

    if ($errors['email']) {
        if ($_COOKIE['email_error'] == '1') {
            $messages[] = '<div class="error">Заполните e-mail.</div>';
        }
        else {
            $messages[] = '<div class="error">E-mail некорректен.</div>';
        }
        // куки не удаляем, ещё понадобится
    }

    if ($errors['bio']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('bio_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните биографию.</div>';
    }

    if ($errors['contract']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('contract_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Подтвердите ознакомление с контрактом.</div>';
    }

    // Складываем предыдущие значения полей в массив, если есть.
    $values = array();
    $values['name'] = ($errors['name'] && $_COOKIE['name_error'] == '1') ? "" : strip_tags($_COOKIE['name_value']);
    $values['email'] = ($errors['email'] && $_COOKIE['email_error'] == '1') ? "" : strip_tags($_COOKIE['email_value']);
    $values['age'] = empty($_COOKIE['age_value']) ? 1990 : strip_tags($_COOKIE['age_value']);
    $values['sex'] = empty($_COOKIE['sex_value']) ? 'male' : strip_tags($_COOKIE['sex_value']);
    $values['quantity'] = empty($_COOKIE['quantity_value']) ? 1 : strip_tags($_COOKIE['quantity_value']);
    $values['super'] = empty($_COOKIE['super_value']) ? '' : strip_tags($_COOKIE['super_value']);
    $values['bio'] = $errors['bio'] ? "" : strip_tags($_COOKIE['bio_value']);
    $values['contract'] = $errors['contract'] ? "0" : strip_tags($_COOKIE['contract_value']);

    // теперь можно удалять оставшиеся куки
    if ($errors['name']) {
        setcookie('name_error', '', 100000);
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
    }


    // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
    // ранее в сессию записан факт успешного логина.
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        $xml = simplexml_load_file($_SESSION['filename']);
        $values = array();
        $values['name'] = strip_tags($xml->name);
        $values['email'] = strip_tags($xml->email);
        $values['age'] = strip_tags($xml->age);
        $values['sex'] = strip_tags($xml->sex);
        $values['quantity'] = strip_tags($xml->quantity);
        $values['bio'] = strip_tags($xml->bio);
        $values['super'] = strip_tags($xml->super);
        if ($_COOKIE['save'] != '1')
            printf('Вход с логином %s, изменение файла %s', $_SESSION['login'], $_SESSION['filename']);
    }

    setcookie('save', '', 100000);

    // Включаем содержимое файла form.php.
    // В нем будут доступны переменные $messages, $errors и $values для вывода
    // сообщений, полей с ранее заполненными данными и признаками ошибок.
    include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    // Проверяем ошибки.
    $errors = FALSE;

    // проверяем name
    if (empty($_POST['name'])) {
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // теперь проверка на корректность
        if (!preg_match("/^[a-zA-Zа-яА-Я ]+$/u", $_POST['name'])) {  // только буквы и пробел
            setcookie('name_error', '2', time() + 24 * 60 * 60);
            $errors = TRUE;
        }

        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }

    // проверяем email
    if (empty($_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        // теперь проверка на корректность
        if (!preg_match("/^([a-z]+)([\w_\.]*)@[a-z]+\.[a-z]+$/u", $_POST['email'])) {
            setcookie('email_error', '2', time() + 24 * 60 * 60);
            $errors = TRUE;
        }

        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }

    // age заполнено по умолчанию
    setcookie('age_value', $_POST['age'], time() + 30 * 24 * 60 * 60);

    // sex заполнено по умолчанию
    setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);

    // quantity заполнено по умолчанию
    setcookie('quantity_value', $_POST['quantity'], time() + 30 * 24 * 60 * 60);

    // проверяем super
    $str = "";
    if (!empty($_POST['super'])) {
        foreach ($_POST['super'] as $v) {
            $str .= $v . " ";
        }
    }
    setcookie('super_value', rtrim($str), time() + 30 * 24 * 60 * 60);  // если сверхсособностей нет, то пустая строка

    // проверяем bio
    if (empty($_POST['bio']) || (trim($_POST['bio']) == "")) {
        setcookie('bio_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
    }

    // проверяем contract
    if (empty($_POST['contract'])) {
        setcookie('contract_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('contract_value', $_POST['contract'], time() + 30 * 24 * 60 * 60);
    }


    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        header('Location: index.php');
        exit();
    }
    else {
        // Удаляем Cookies с признаками ошибок.
        setcookie('name_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('contract_error', '', 100000);
    }

    // или изменяем данные, или создаем по новой
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])){
        $xml = simplexml_load_file(/*$_COOKIE['xml_cookie']*/$_SESSION['filename']);
        $xml->name = $_POST['name'];
        $xml->email = $_POST['email'];
        $xml->age = $_POST['age'];
        $xml->sex = $_POST['sex'];
        $xml->quantity = $_POST['quantity'];
        $xml->bio = $_POST['bio'];
        $str = "";
        foreach($_POST['super'] as $i)
            $str .= $i . " ";
        $xml->super = rtrim($str);

        $s = $xml->AsXML();
        $name = $_SESSION['filename'];
        $xml->saveXML("$name");
    }
    else {
        // Сохранение в XML-документ.
        // Создаем новый XML-документ с корневым тегом document в памяти.
        $xml = new SimpleXMLElement('<document/>');
        $child = $xml->AddChild('name', $_POST['name']);
        $child = $xml->AddChild('email', $_POST['email']);
        $child = $xml->AddChild('age', $_POST['age']);
        $child = $xml->AddChild('sex', $_POST['sex']);
        $child = $xml->AddChild('quantity', $_POST['quantity']);
        $child = $xml->AddChild('bio', $_POST['bio']);
        $str = "";
        if (!empty($_POST['super'])) {
            foreach ($_POST['super'] as $i)
                $str .= $i . " ";
        }
        $child = $xml->AddChild('super', rtrim($str));
        // рандомно формируем логин и пароль
        $login = uniqid();
        $pass = uniqid();
        $child = $xml->AddChild('login', $login);
        $child = $xml->AddChild('pass', md5($pass));

        setcookie('login', $login, time() + 30 * 24 * 60 * 60);
        setcookie('pass', $pass, time() + 30 * 24 * 60 * 60);

        // Сохраняем XML в строку.
        $s = $xml->AsXML();
        // Имя файла.
        $f = "xml/" . uniqid() . '.xml';

        // Записываем в файл.
        file_put_contents($f, $s);
    }

    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');

  // Делаем перенаправление.
  // Если файл не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
  // Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
    header(sprintf('Location: %s', $_SERVER['PHP_SELF']));
}