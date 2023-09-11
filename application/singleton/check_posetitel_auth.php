<?php
// Проверка авторизованного посетителя, перешедшего в статус юзер
class CheckPosetitelAuth
{
	private static $_instance;
	private $sql;
	private $users_id = null;
	private $users_ok = false;
	private $users_select = null;

	public function __construct()
	{
		$this->sql = MysqlEx::_Instance();
	}
	
	// Шаблон одиночка
	public static function _Instance() 
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new CheckPosetitelAuth();
		}
		return self::$_instance;
	}
	
	public function run()
	{
		
		if($this->setUsersIdVar() && $this->selectUsers())
		{
			$this->users_ok = true;
			// Переброс в гугл заблокированного посетителя
			if ($this->users_select['users_block'] == 'Y')
			{
				//CheckPosetitel::_Instance()->goGoogle();				
				//exit(0);
				return false;
			}
		}
		else $this->AuthHeader();
		
		return $this->users_ok;
	}
	
	
	private function AuthHeader()
	{
	// Проверяем был ли посетитель авторизован
		if ( !$this->checkPoint('users_id')) 
		{
			// Ищем постетителя в БД по useragent i starttime либо useragent i ip
			if (!$this->searchUser())
			{
				// Если узер не нашёлся, добавляем его в БД и авторизуем
				$this->insertUser();
			}else $this->selectUsers();
		} else $this->selectUsers();
		
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	
			// Авторизация юзера
	
	// Ищем постетителя в БД по useragent i starttime либо useragent i ip
	private function searchUser()
	{
		$starttime = isset($_SESSION['starttime']) && intval($_SESSION['starttime']) ? $_SESSION['starttime'] : 0;
		//if (!$starttime) $starttime = (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime'])) ? intval($_COOKIE['starttime'] : 0;
		
		if (!$starttime)
		{
			if (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime']) )
			{
				$starttime = $_COOKIE['starttime'];
			}
			else 
			{
			// Если куки не установлены, блокируем посетителя
			$query_f = 'INSERT INTO `'.ConfigMySQL::db_prefix.'posetiteliyn`(
				`posetiteli_agent`, 
				`posetiteli_date`, 
				`posetiteli_block`, 
				`posetiteliYN_ip` ,
				`posetiteli_starttime`, 
				`posetiteliYN_comment`
				) VALUES(
				\''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\' ,
				NOW() ,
				\'Y\' ,
				INET_ATON(\''.getenv('REMOTE_ADDR').'\') ,
				0 ,
				\'off session i cookie\'
				);';
		
			$this->sql->sql_query($query_f);
			
		
			// Перенаправляем посетителя в гугл:
			CheckPosetitel::_Instance()-> goGoogle();
			}
		}
		
		$query = "SELECT 
		`users_id` ,
		`posetiteli_starttime`
	FROM 
		`".ConfigMySQL::db_prefix."users` 
	WHERE 
		( `posetiteli_starttime` = ".$starttime." 
		 AND
		 `users_agent` = '". ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) ."'
		 )
	 OR
		 ( `users_agent`= '". ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) ."'
		 AND
		 `users_ip` = INET_ATON('".getenv('REMOTE_ADDR')."') 
		 )
	LIMIT 1;";
	
			$this->sql->sql_query($query);
		if ($massiv = $this->sql->sql_fetch_array())
		{
			$this->users_id = $massiv['users_id'];
			
			$_SESSION['users_id'] = $massiv['users_id'];
			setcookie('users_id', $massiv['users_id'], time(true) + 3600 * 24 * 3650, '/');
			$_COOKIE['users_id'] =  $massiv['users_id'];
			
			$_SESSION['starttime'] = $massiv['posetiteli_starttime'];
			setcookie('starttime', $massiv['posetiteli_starttime'], time(true) + 3600 * 24 * 3650, '/');
			$_COOKIE['starttime'] =  $massiv['posetiteli_starttime'];
			
			$this->users_ok = true;
			return true;
		}
		else return false;
	}
	
	
	// Если узер не нашёлся, добавляем его в БД и авторизуем
	private function insertUser()
	{
	
		$starttime = isset($_SESSION['starttime']) && intval($_SESSION['starttime']) ? $_SESSION['starttime'] : 0;
		if (!$starttime)
		{
			if (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime']) )
			{
				$starttime = $_COOKIE['starttime'];
			}
		}
	
		$query = "INSERT INTO `".ConfigMySQL::db_prefix."users` (

		`users_ip`, 
		`users_agent`, 
		`users_ref`, 

		`users_reg`, 
		
		`posetiteli_starttime`
		)  VALUES(

		 INET_ATON('".getenv('REMOTE_ADDR')."') ,
		 '". ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) ."' ,
		 '". (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null) ."' ,
		 NOW() ,
		 ".$starttime."
		) ;";
	
			$this->sql->sql_query($query);
			

	}
	
	
	
	
	
	///////////////////////////////////////////////////////////////////////////////////
	
			// Возвращаем публичные данные юзера
	
	
	/*
	users_id - вернёт индекс юзера, CheckPosetitelAuth::_Instance()->getUsersArr()[0];
	users_view - посмотренные видосы CheckPosetitelAuth::_Instance()->getUsersArr('users_posmotrel')[];
	users_like - от посетителя лайки на видосах CheckPosetitelAuth::_Instance()->getUsersArr('users_like')[];
	users_download - закаченные видосы посетителем CheckPosetitelAuth::_Instance()->getUsersArr('users_kachnul')[];
	users_posts - оставленные посты посетителем CheckPosetitelAuth::_Instance()->getUsersArr('users_perepiska')[];
	*/
	public function getUsersArr($point = 'users_view')
	{
		if ($this->users_ok)
			return (isset($this->users_select[$point]) && strlen($this->users_select[$point])) ? explode(',', $this->users_select[$point]) : null ;
		else return null;
	} // вернёт индексный массив конкретного элемента юзера с БД
	
	// users_id - вернёт индекс юзера, CheckPosetitelAuth::_Instance()->getUsersId();
	public function getUsersId($point = 'users_id')
	{
		if ($this->users_ok)
			return (isset($this->users_select[$point]) ? $this->users_select[$point] : null );
		else return null;
	} // вернёт строку конкретного элемена юзера с БД
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////
	
	
	
	// Проверяем посетителя в сессиях и куках
	/*
		$user = ...
	users_id - Проверяем был ли посетитель авторизован
	users_posmotrel - Проверяем есть ли посмотренные видосы
	users_like - Проверяем, есть ли от посетителя лайки на видосах
	users_kachnul - Проверяем, есть ли закаченные видосы посетителем
	users_perepiska - Проверяем, есть ли оставленные посты посетителем
	*/
	private function checkPoint($user0 = 'users_id')
	{
		if (isset($_SESSION[$user0]) && intval($_SESSION[$user0]) && ($user0 == 'users_id'))
		{
			$this->users_id = intval($_SESSION[$user0]);
			return true;
		}
		else if (isset($_COOKIE[$user0]) && intval($_COOKIE[$user0]) &&  ($user0 == 'users_id'))
		{
			$this->users_id = intval($_COOKIE[$user0]);
			return true;
		}
		else return false;
	}
	
	// Устанавливаем идентификатор узера в переменную этого класса
	private function setUsersIdVar()
	{
		$user1 = 'users_id';
		$this->users_id = ( isset($_SESSION[$user1]) && intval($_SESSION[$user1]) ) ? $_SESSION[$user1] : (isset($_COOKIE[$user1]) && intval($_COOKIE[$user1])) ? $_COOKIE[$user1] : 0;
		if ($this->users_id > 0) 
			return true;
		else return false;
	}
	
	// Извлекаем данные юзера из БД и заносим в сессии и куки
	private function selectUsers()
	{
		$query = "SELECT 
		`users_id`, 
		INET_NTOA( `users_ip` ), 
		`users_agent`, 
		`users_ref`, 
		`users_phone`, 
		`users_reg`, 
		`dostup_id`, 
		`users_posts`, 
		`users_like`, 
		`users_view`, 
		`users_download`, 
		`users_block`, 
		`users_dateblock` ,
		`posetiteli_starttime`
	FROM 
		`".ConfigMySQL::db_prefix."users` 
	WHERE 
		 `users_id`= ".intval($this->users_id)."
	LIMIT 1;";
	
	
			$this->sql->sql_query($query);
			if($massiv = $this->sql->sql_fetch_array())
			{
				$this->users_select = $massiv;
				$this->users_ok = true;
				return true;
			}
			else return false;
	}
}
?>