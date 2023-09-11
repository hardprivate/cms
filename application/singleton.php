<?php
// Подключает все модули шаблонов одиночки

// Определяет константу для всех классов шаблона одиночки
define('_SINGLETON_DIR', 'singleton/', true);

// Подключение файла конфигурации со статическими константами для БД
require_once('config.php');
// Подключение статических имён: переменных и констант
require_once(_SINGLETON_DIR.'names.php');
// Подключение базы данных
require_once(_SINGLETON_DIR.'mysql.php');

// Проверка посетителя
require_once(_SINGLETON_DIR.'check_posetitel.php');
// Проверка авторизованного посетителя
require_once(_SINGLETON_DIR.'check_posetitel_auth.php');

// Подключение транслитерации
require_once(_SINGLETON_DIR.'transliteration.php');
// Подключение отправки смс на телефон
// require_once(_SINGLETON_DIR.'sms.php');
// Подключение граббера dom для парсинга html/xml
// require_once(_SINGLETON_DIR.'simplehtmldom_1_5/simple_html_dom.php');
// Подключение левого меню из базы данных
require_once(_SINGLETON_DIR.'menu.php');

?>