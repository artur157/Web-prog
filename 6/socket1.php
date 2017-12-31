<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);

$service_port = "80";  // задаем порт HTTP
$address = "192.168.56.101";  // задаем адрес

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // создаем сокет
if ($socket === false)
    echo "Ошибка создания сокета: " . socket_strerror(socket_last_error()) . "\n";

$result = socket_connect($socket, $address, $service_port);  // установка соединения
if ($result === false)
    echo "Ошибка соединения: " . socket_strerror(socket_last_error($socket)) . "\n";


$in = "GET /4/fragment.php HTTP/1.1\r\n";
$in .= "Host: mydomain\r\n";
$in .= "Connection: close\r\n\r\n";
$out = '';

socket_write($socket, $in, strlen($in)); // отправляем запрос

$bytes = socket_get_option ($socket, SOL_SOCKET, SO_RCVBUF); // считываем ответ

while ($out = socket_read($socket, $bytes))
    echo $out . "<br>";

socket_close($socket); // закрыли сокет

?>