<?php

/**
 * Задача 16. Реализовать RESTful веб-сервис для получения списка сохраненных результатов и самих результатов в XML,
 * отправки и сохранения новых результатов для задачи 7. Запрос на сохранение отправляет XML-документ.
 * Использовать HTTP-авторизацию. Провести аудит безопасности кода веб-сервиса.
 **/

/*
Пример URI запроса с параметром:
/?file=my_xml_file_name
или
/index.php?file=my_xml_file_name

*/

// Определяем метод запроса.
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        service_get();
        break;
    case 'POST':
        service_post();
        break;
    default:
        header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
}

// Обрабатываем GET-запрос.
function service_get() {
    header('Content-Type: text/html; charset=utf-8');

    if (empty($_GET['file'])) {
        // выдавать XML со списком URI ресурсов, cоответствующих ранее сохраненным XML-файлам.
        $xml = new SimpleXMLElement('<document/>');
        foreach (glob("xml/*.xml") as $filename) {
            $child = $xml->AddChild("uri", $filename);
        }
    }
    else {
        // если файл $_GET['file'] сущ., то выдавать содержимое без логина и пароля.
        if (file_exists("xml/" . str_replace(array('/', '\\'), '', $_GET['file']) . ".xml")){
            $xml = simplexml_load_file("xml/" . str_replace(array('', '\\'), '', $_GET['file']) . ".xml");
            unset($xml->login);
            unset($xml->pass);
        } else {  // при отсутствии файла выдавать ошибку 404.
            header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
            exit();
        }
    }
    //header("Content-type: text/xml");
    print($xml->AsXML());  // выдаем xml: либо список файлов, либо содержимое одного файла
}

// Обрабатываем POST-запрос.
function service_post() {
    // читаем присланные в запросе данные в переменную.
    $file = file_get_contents("php://input");
    $xml = simplexml_load_string($file);  // неправильно формируется файл

    // проверить данные XML
    /*if ($xml && !empty($_GET['file'])){ //   $_POST['xml_request']
        // сохраняем в файл и выдаваем перенаправление на его URI.
        file_put_contents("xml/" . str_replace(array('/', '\\'), '', $_GET['file']) . ".xml", $xml->AsXML());
        header("Location: " . $_SERVER['PHP_SELF'] . "/?file=" . str_replace(array('/', '\\'), '', $_GET['file']));*/
    if(!empty($xml->name) && !empty($xml->email) && !empty($xml->age) && !empty($xml->sex)&& !empty($xml->quantity)
        && !empty($xml->super) && !empty($xml->bio)&& !empty($xml->login)&& !empty($xml->pass)){
        $xmldoc = new SimpleXMLElement('<document/>');
        $child = $xmldoc->AddChild('name', strip_tags($xml->name));
        $child = $xmldoc->AddChild('email', strip_tags($xml->email));
        $child = $xmldoc->AddChild('age', strip_tags($xml->age));
        $child = $xmldoc->AddChild('sex', strip_tags($xml->sex));
        $child = $xmldoc->AddChild('quantity', strip_tags($xml->quantity));
        $child = $xmldoc->AddChild('super', strip_tags($xml->super));
        $child = $xmldoc->AddChild('bio', strip_tags($xml->bio));
        $child = $xmldoc->AddChild('login', strip_tags($xml->login));
        $child = $xmldoc->AddChild('pass', strip_tags($xml->pass));
        $s = $xmldoc->AsXML();
        $file_name = uniqid();

        $f = "xml/" . $file_name . ".xml"; // str_replace(array('/', '\\'), '', $_GET['file'])
        file_put_contents($f, $s);
        header("Location: " . $_SERVER['PHP_SELF'] . "/?file=" . $file_name);
    } else {
        print "xml file is incorrect";
        header("HTTP/1.0 404 Not Found");
    }
}