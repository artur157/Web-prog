<?php
//ini_set('display_errors','On');
//phpinfo();
//exit;

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
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('save', '', 100000);
        // Если есть параметр save, то выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
    }

    // Складываем признак ошибок в массив.
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['contract'] = !empty($_COOKIE['contract_error']);

    // Выдаем сообщения об ошибках.
    if ($errors['name']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('name_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните имя.</div>';
    }

    if ($errors['email']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('email_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Заполните e-mail.</div>';
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
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['age'] = empty($_COOKIE['age_value']) ? 1990 : $_COOKIE['age_value'];
    $values['sex'] = empty($_COOKIE['sex_value']) ? 'male' : $_COOKIE['sex_value'];
    $values['quantity'] = empty($_COOKIE['quantity_value']) ? 1 : $_COOKIE['quantity_value'];
    $values['super'] = empty($_COOKIE['super_value']) ? '' : $_COOKIE['super_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['contract'] = empty($_COOKIE['contract_value']) ? '0' : '1';

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
      setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }

    // проверяем email
    if (empty($_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }

    // age заполнено по умолчанию
    setcookie('age_value', $_POST['age'], time() + 30 * 24 * 60 * 60);

    // sex заполнено по умолчанию
    setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);

    // quantity заполнено по умолчанию
    setcookie('quantity_value', $_POST['quantity'], time() + 30 * 24 * 60 * 60);

    // проверяем super
    if (!empty($_POST['super'])) {
        $str = "";
        foreach ($_POST['super'] as $v) {
            $str .= $v;
        }
        setcookie('super_value', $str, time() + 30 * 24 * 60 * 60);
    }

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

  // Сохранение в XML-документ.
  // Создаем новый XML-документ с корневым тегом document в памяти.
  $xml = new SimpleXMLElement('<document/>');
  // Добавляем элемент fio.
  // В суперглобальном массиве $_POST PHP хранит все параметры, переданные в текущем запросе
  // через urlencoded данные в сущности запроса.
    $child = $xml->AddChild('name', $_POST['name']);
    $child = $xml->AddChild('email', $_POST['email']);
    $child = $xml->AddChild('age', $_POST['age']);
    $child = $xml->AddChild('sex', $_POST['sex']);
    $child = $xml->AddChild('quantity', $_POST['quantity']);
    $child = $xml->AddChild('bio', $_POST['bio']);
    $str = "";
    foreach($_POST['super'] as $i)
        $str .= $i . " ";
    $child = $xml->AddChild('super', $str);

  // Аналогично добавляем остальные элементы,
  // аттрибуты добавляем через $element->AddAttribute().

// *************
// Тут необходимо сохранить в XML все данные формы.
// *************

  // Сохраняем XML в строку.
  $s = $xml->AsXML();
  // Имя файла.
  $f = "xml/" . uniqid() . '.xml';

// *************
// Тут необходимо обеспечить уникальность имен файлов одним из трех способов:
// 1) вести счетчик последнего имени файла, функции file_get_contents/file_put_contents
// 2) использовать в имени файла текущее время, например time()
// 3) использовать GUID (Google: php guid)
// *************
  // Записываем в файл.
  file_put_contents($f, $s);

    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');

  // Делаем перенаправление.
  // Если файл не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
  // Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
    header('Location: index.php');
}