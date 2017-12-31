<?php
ini_set('display_errors','On');
header('Content-Type: text/html; charset=UTF-8');

	$xml = new SimpleXMLElement('<document/>');
    $child = $xml->AddChild('name', /*$_POST['name']*/"Adfh");
    $child = $xml->AddChild('email', /*$_POST['email']*/"a@mail.ru");
    $child = $xml->AddChild('age', /*$_POST['age']*/1990);
    $child = $xml->AddChild('sex', /*$_POST['sex']*/"male");
    $child = $xml->AddChild('quantity', /*$_POST['quantity']*/4);
    $child = $xml->AddChild('bio', /*$_POST['bio']*/"abc");
    $str = "";
    /*if (!empty($_POST['super'])) {
        foreach ($_POST['super'] as $i)
            $str .= $i . " ";
    }*/
    $child = $xml->AddChild('super', rtrim($str));
    // рандомно формируем логин и пароль
    $login = uniqid();
    $pass = uniqid();
    $child = $xml->AddChild('login', $login);
    $child = $xml->AddChild('pass', md5($pass));

    $s = $xml->AsXML();

	$url = "192.168.56.101/16/service.php?file=new";  //192.168.56.101  uniqid()
    //$url = "service.php?file=new";  // ошибка в написании алреса? попробовать ip вместо имени

    $opts = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/xml',
            'content' => $s
        )
    );

    $context = stream_context_create($opts);

    // методом POST отправляем xml service.php
    $result = file_get_contents($url, false, $context);  // проблема в этой строке

   header("Content-type: text/xml");
   print($s);
?>
