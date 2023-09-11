<?php
class ConfigMySQL
{
	// Соединение с базой данных

	const db_host = 'localhost';
	const db_name = 'ha';
	const db_pass = '';
	const db_database = 'ha';
	const db_prefix = 'ha_';

	public function __construct()
	{
		
	}
	
}

	// Переменные для основной шкурки

class ConfigHTML
{
	public static $charset = 'utf-8';
	public static $description = '';
	public static $keywords = '';
	public static $script_src_array = array('/js/menu.js','/js/spoilers_scr.js');
	public static $css_src_array = array('/css/style.css');

	public static $html_title = 'HardPrivate.com ';
	public static $html_h3 = 'Танцы девушек без одежды / Girls dancing naked';
	public static $html_h3_subkat = '';
}

//ConfigHTML::$html_title = ConfigHTML::$html_title.ConfigHTML::$html_h3_subkat;

?>
