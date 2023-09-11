<?php
// Транслитерация
class Transliteration
{
	private static $_instance;

	public function __construct()
	{
		
	}
	// Шаблон одиночка
	public static function _Instance() 
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new Transliteration();
		}
		return self::$_instance;
	}
	
	// Транслитерация строк.
	public function transliterate($st) 
	{
		$st = strtr($st, 
			"абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
			"abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
		);
		$st = strtr($st, array(
		'ё'=>"yo",    'х'=>"h",  'ц'=>"ts",  'ч'=>"ch", 'ш'=>"sh",  
		'щ'=>"shch",  'ъ'=>'',   'ь'=>'',    'ю'=>"yu", 'я'=>"ya",
		'Ё'=>"Yo",    'Х'=>"H",  'Ц'=>"Ts",  'Ч'=>"Ch", 'Ш'=>"Sh",
		'Щ'=>"Shch",  'Ъ'=>'',   'Ь'=>'',    'Ю'=>"Yu", 'Я'=>"Ya",
		));
		return $st;
	}
	
	// Делает валидный url-адрес из строки
	public function str2url($str) 
	{
		// переводим в транслит
		$str = rus2translit($str);
		// в нижний регистр
		$str = strtolower($str);
		// заменям все ненужное нам на "-"
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		// удаляем начальные и конечные '-'
		$str = trim($str, "-");
		return $str;
	}

}
?>