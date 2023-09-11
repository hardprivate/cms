<?php
// Собирает ядро

// Подключает все файлы шаблонов одиночки
require_once('singleton.php');
// Подключает запросы в БД
// require_once('sql.php')
// Инициализирует проверку посетителя
require_once('check.php');

// запускает проверку посетителя на входе
$check = new Check();
Names::$n_reuestUriArray = explode('/', $_SERVER['REQUEST_URI']);
//$check->Run();
if($check->Run())
{
	// Шаблон проектирования ядра MVC

	// Первые три строки будут подключать файлы ядра
	require_once('core/model.php');
	require_once('core/view.php');
	require_once('core/controller.php');
	//Подключают файл с классом маршрутизатора и запускают его на выполнение вызовом статического метода start.
	require_once('core/route.php');
	Route::start(); // запускаем маршрутизатор
}
else
{
	switch(Names::$n_reuestUriArray[1])
	{
		case 1: header("Location: /");
			break;
		case 'style.css':
			break;
		default:
			if(isset($_COOKIE['block']) && $_COOKIE['block'] === 'Y') exit(0);
			else
			header("Location: i.html");
	}
	// paycore.io
	//header("Location: https://sitey.ru/");
	//require_once('curl.php');
}

//if(Names::$n_reuestUriArray[1] == '404') header("Location: /");
//if(Names::$n_reuestUriArray[1] == 403) header("Location: /");
?>