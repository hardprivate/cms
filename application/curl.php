<?php
$ch = curl_init();
$ch_url_search = 'sitey.ru';
$ch_url_replace = 'hardprivate.com';

$ch_url_set = 'https://'.$ch_url_search.$_SERVER['REQUEST_URI'];

// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, $ch_url_set);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
//curl_setopt($ch, CURLOPT_PROXY, "192.168.100.3:3128");
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0'); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// загрузка страницы и выдача её браузеру
$data = curl_exec($ch);

//$data = preg_replace("!<noindex>(.*?)</noindex>!si", "", $data);
//$data = str_replace('http:', 'https:', $data );
//$data = str_replace('<a href="http://'.$ch_url_search , '<a href="https://'.$ch_url_replace , $data); 
//$data = str_replace('http://'.$ch_url_search, 'https://'.$ch_url_replace, $data ); 
$data = str_replace($ch_url_search , $ch_url_replace, $data);

	echo $data;
	
// завершение сеанса и освобождение ресурсов
curl_close($ch);
?>