#include <iostream>
#include <winsock2.h>
#include <WS2tcpip.h>
#include <string>
#include <sstream>
#pragma comment(lib, "ws2_32.lib")
#pragma comment(lib,"wsock32.lib")
#pragma warning(disable:4996)
using namespace std;

int num_of_ex = 4;

string get_source(string _requete)
{
	WSADATA WSAData;  // здесь инфа о производителе библиотеки
	WSAStartup(MAKEWORD(2, 0), &WSAData); // подг. к раб. библ. Winsock

	SOCKET sock;
	sock = socket(AF_INET, SOCK_STREAM, 0);
	SOCKADDR_IN sin;  // адрес и порт удаленного узла с которым устанавливается соединение

	string srequete = _requete;   // доформировываем запрос
	srequete += "Host: mydomain\r\n";
	srequete += "Connection: close\r\n";
	srequete += "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\r\n";
	srequete += "Accept-Language: fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3\r\n";
	srequete += "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
	srequete += "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3\r\n";
	srequete += "\r\n";
	if (num_of_ex == 4) srequete += "message=Hello,%20world!\r\n\r\n";

	char crequete[512];
	strncpy(crequete, srequete.c_str(), srequete.size() + 1); // преобр. строку в массив символов

	sin.sin_addr.s_addr = inet_addr("192.168.56.101");  // задали адрес 
	sin.sin_family = AF_INET;  // через интернет
	sin.sin_port = htons(80); // задали порт HTTP

	connect(sock, (SOCKADDR *)&sin, 256);  // установка соединения с удаленным узлом
	send(sock, crequete, strlen(crequete), 0);  // отправили запрос

	string out = "", source = "", Con_len = "Content-Length: ", end_ = "\r\n\r\n";  // source - ответ
	char buffer[1], ret[512];
	int i = 0, pos = 0, start_ = 0, start_2 = 0;
	bool b = false;  // признак считываемости Content-Length

	do
	{
		i = recv(sock, buffer, sizeof(buffer), 0);  // прием данных
		source = source + buffer[0];
		if (buffer[0] == Con_len[start_]) start_++;
		else start_ = 0;

		if (start_ == Con_len.length())
		{
			b = true;
		}

		if (b) // если считали Content-Length
		{
			if (buffer[0] == '\r') b = false;  // если числа нет, то не нашли
			else
			{
				if (buffer[0] != ':' && buffer[0] != ' ')
				{
					out += buffer[0];   // формируем строку сост. из размера ответа
				}
			}
		}

		if (buffer[0] == end_[start_2])
		{
			start_2++;
			if (start_2 == end_.length()) break;
		}
		else start_2 = 0;

	} while (true);


	i = recv(sock, ret, atoi(out.c_str()), 0);  // получаем ответ в найденном размере
	int size = min(atoi(out.c_str()), 2000);

	// добавляем сколько нужно символов из массива ret
	for (int j = 0; j < size; ++j)
		source += ret[j];

	closesocket(sock); // закрытие соединения и уничтожение сокета
	WSACleanup();  // деинициализация библиотеки и освобождение ресурсов 

	return source;
}

int main(){
	string s = "";
	switch (num_of_ex){
		case 1: s = get_source("GET /4/fragment.php HTTP/1.1\r\n"); break; 
		case 2: s = get_source("HEAD /4/file.tar.gz HTTP/1.1\r\n"); break;
		case 3: s = get_source("HEAD /4/image.png HTTP/1.1\r\n"); break;
		case 4: s = get_source("POST /4/index1.php HTTP/1.1\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: 23\r\n"); break;  // message=Hello,%20world!\r\n
		case 5: s = get_source("GET /4/file.tar.gz HTTP/1.1\r\nRange: bytes=0-99\r\n"); break;
		case 6: s = get_source("HEAD /4/index1.php HTTP/1.1\r\n"); break;
	}
	cout << s << endl;
	system("pause");
	return 0;
}

