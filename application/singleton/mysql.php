<?php
final class MysqlEx extends ConfigMySQL
{
	private static $_instance;

	private $db_connect_id;
	private $db_query_result;
	private $db_error = false;

	public function __construct()
	{
		$this->db_connect_id = mysql_connect(parent::db_host, parent::db_name, parent::db_pass);
		if($this->db_connect_id)
		{
			//if(!parent::db_database)
			//{
				//$this->sql_query('SET NAMES utf8');
				$dbselect = mysql_select_db(parent::db_database);
				if(empty($dbselect))
				{
					@mysql_close($this->db_connect_id);
					$this->db_error = (string) 'Не удалось выбрать базу данных '.$this->db_database;
				}
				else
				{
					$this->sql_query('SET NAMES utf8');
				}
			//}
			//$this->db_error = (string) 'Не указано имя базы данных';
		}
		else
		{
			$this->db_error = (string) 'Не удалось соединиться с базой данных: логин, пароль или хост указаны не верно';
		}
		
		if ($this->db_error) 
		{
			$this->db_connect_id = null;
			echo $this->db_error.'<br /><br />'.mysql_error();
			exit();
		}

	}
	
	// Шаблон Одиночка
	public static function _Instance() 
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new MysqlEx();
		}
		return self::$_instance;
	}
	
	public function sql_close()
	{
		if($this->db_connect_id)
		{
			if($this->db_query_result)
			{
				@mysql_free_result($this->db_query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	// Сохраняет результат обращения к базе данных в свойстве класса
	public function sql_query($query_f)
	{
		$this->db_query_result = mysql_query($query_f) OR die(mysql_error());
	}
	
	// Возвращает ассоциативный массив
	public function sql_fetch_array()
	{
		return mysql_fetch_array($this->db_query_result, MYSQL_ASSOC);
	}
	
	// Удаление обратных слэшэй в ассоциативном массиве
	public function massiv_stripslashes(array &$query_f)
	{	
		//$i = (int) count($query_f)
		//while(--$i > -1)

		foreach ($query_f as $key => $value)
		{
			$db_fetch_array[$key] = StripSlashes($value);
			//$query_f[$i] = StripSlashes($value);
		}
	}

	// Возвращает декодированную строку				 
	public function myUrlEncode($string) 
	{
		$entities = array('%25','%20','%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C',  '%3F', '%23', '%5B', '%5D');
		$replacements = array('%',' ','!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",",  "?", "#", "[", "]");
		return str_replace($replacements, $entities,  $string);
	}

}

// Инициализация базы данных: шаблон одиночка
MysqlEx::_Instance();

?>