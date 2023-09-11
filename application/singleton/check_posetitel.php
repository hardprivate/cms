<?php
// Проверка посетителя на входе
class CheckPosetitel
{
	private static $_instance;
	private $sql;
	private $startTime = 0;
	private $users_id = 0;

	public function __construct()
	{
		$this->sql = MysqlEx::_Instance();
	}
	
	// Шаблон одиночка
	public static function _Instance() 
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new CheckPosetitel();
		}
		return self::$_instance;
	}
	
	public function run()
	{
		$this->StartTime();
		$this->startTime = ($this->startTime) ? $this->startTime : $this->GetStartTime();
		$this->users_id = ($this->users_id) ? $this->users_id : $this->GetUsersId();
		$nocheck = false;
		
		
		
		if (!$this->getMicrotime() ) { $this->setMicrotime(); $nocheck = true; }
		
		// Блокируем посетителя, если он пришёл с флагом блокировки
		if (  isset(Names::$n_reuestUriArray[1]) && 
				( 
					(Names::$n_reuestUriArray[1] == 1) || 
					(Names::$n_reuestUriArray[1] == 'style.css') 
				) 
			 //) || 
			 //(
			//	isset($_POST['b']) && ($_POST['b'] == 1)
			// )
			)
		{
			//if(isset($_POST['b']) && ($_POST['b'] == 1) ) { Names::$n_reuestUriArray[1] = 1; }
			$this->setBlockPosetitel();
			$this->SetBlock_SQL_posetitelyn();
		}
		
		// Снимаем блокировку посетителя, если он пришёл с флагом разблокировки
		/*
		if ( ( isset(Names::$n_reuestUriArray[1]) && 
				( 
					Names::$n_reuestUriArray[1] == 0
				) 
			 ) || 
			 (
				isset($_POST['b']) && ($_POST['b'] == 0)
			 )
			)
		{
			if(isset($_POST['b']) && ($_POST['b'] == 0) ) { Names::$n_reuestUriArray[1] = 0; }
			$this->UnsetBlockPosetitel();
			$this->SetBlock_SQL_posetitelyn();
		}
		*/
	
		
		// Если посетителя ранее не было, добавляем его в БД
		if ($nocheck)
		{
			// 
						
			//if ($this->getBlockPosetitel() || 
			//	$this->checkStopListPosetitel() ||  
			//	$this->checkBlockPosetitel() || 
			//	$this->checkBlockPosetitel2())
			//{
				//$this->setBlockPosetitel();

			//}
		
			$this->insertPosetitel();
		}
		
		// снимает с посетителя блокировку, если это явно указано в БД и возвращает ложь
		// // Добавляет блокировку в БД, если она имеется в сессии
		if ($this->unblockSet() || $this->getBlockPosetitel() ) 
		{
			$this->SetBlock_SQL_posetitelyn();
			//$this->goGoogle();
		}
	}
	
	// Блокируем посетителя в БД
	private function SetBlock_SQL_posetitelyn()
	{
		$posetiteliYN_id = 0;
			$query_select = 'SELECT `posetiteliYN_id` FROM `'.ConfigMySQL::db_prefix.'posetiteliyn`
						WHERE
							`posetiteli_agent` = \''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\'
						--	коммент	AND
						--	коммент	`posetiteliYN_ip` = INET_ATON(\''.getenv('REMOTE_ADDR').'\')
							AND `posetiteli_starttime` = '.$this->startTime.'
							'.($this->users_id ? ' OR `users_id` = '.$this->users_id : '').'
						LIMIT 1';
						
			$this->sql->sql_query($query_select);
			if ($massiv = $this->sql->sql_fetch_array())
			{
				$posetiteliYN_id = $massiv['posetiteliYN_id'];
			}
			
			if($posetiteliYN_id)
			$query_if = '			
				UPDATE `'.ConfigMySQL::db_prefix.'posetiteliyn`
					SET `posetiteli_block` = \''.($this->getBlockPosetitel() ? 'Y':'N').'\'
					'.($this->users_id ? ' , `users_id` = '.$this->users_id : '').'
				WHERE 
					`posetiteliYN_id` = '.$posetiteliYN_id.'
					
				--	коммент		`posetiteli_agent` = \''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\'
				--	коммент		AND
				--	коммент		`posetiteliYN_ip` = INET_ATON(\''.getenv('REMOTE_ADDR').'\')
				LIMIT 1; ';
			
			else
			$query_if = 
				'INSERT INTO `'.ConfigMySQL::db_prefix.'posetiteliyn`(
				`posetiteli_agent`, 
				`posetiteli_date`, 
				`posetiteli_block`, 
				`posetiteliYN_ip` ,
				`users_id` ,
				`posetiteli_starttime`, 
				`posetiteliYN_comment`
				) VALUES(
				\''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\' ,
				NOW() ,
				\'Y\' ,
				INET_ATON(\''.getenv('REMOTE_ADDR').'\') ,
				'.$this->users_id.' ,
				'.$this->startTime.' ,
				\'get b from adress\'
				)
			;';
		
			$this->sql->sql_query($query_if);
	
	}
	
	private function GetUsersId()
	{
		$users_id = (isset($_SESSION['users_id']) && intval($_SESSION['users_id'])) ? $_SESSION['users_id'] :0;
		if (!$users_id)
		{
			$users_id = (isset($_COOKIE['users_id']) && intval($_COOKIE['users_id'])) ? $_COOKIE['users_id'] :0;
		}
		return $users_id;
	}

	
	private function GetStartTime()
	{
		$startTime = (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) ? $_SESSION['starttime'] :0;
		if (!$startTime)
		{
			$startTime = (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime'])) ? $_COOKIE['starttime'] :0;
		}
		return $startTime;
	}
	
	// Снимаем блокировку с посетителя при явном указании не блокировать в БД
	private function unblockSet()
	{
		$out = array(); // Контент выходных данных

		$startTime = ($this->startTime) ? $this->startTime : $this->GetStartTime();
		
		$query_f = 'SELECT
		`posetiteli_block` 
		FROM
		`'.ConfigMySQL::db_prefix.'posetiteliyn`
		WHERE
		`posetiteli_starttime` = '.$startTime.'
		AND
		`posetiteli_agent` = \''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\'
		ORDER BY `posetiteliYN_id` DESC
		LIMIT 1';
		
		$this->sql->sql_query($query_f);
		if($massiv = $this->sql->sql_fetch_array())
		{
			if ( $massiv['posetiteli_block'] == 'N' )
			{
				$_SESSION['block'] = 'N';
				setcookie("block", 'N', time(true) + 3600 * 24 * 3650, '/');
				$_COOKIE['block'] = 'N';
			}
			elseif ( $massiv['posetiteli_block'] == 'Y' )
			{
				$_SESSION['block'] = 'Y';
				setcookie("block", 'Y', time(true) + 3600 * 24 * 3650, '/');
				$_COOKIE['block'] = 'Y';
				return true;
			}
		}
		
		return false;
	}
	
	// Посетитель пришёл впервые, starttime - неизменяемая константа для идентификации в совокупности с агентом
	private function StartTime()
	{
	
		if ( (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) || (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime'])) )
		{
		}
		else
		{
			$starttime_first = time(true);
			$_SESSION['starttime'] = $starttime_first;
			 setcookie('starttime', $starttime_first, $starttime_first + 3600 * 24 * 3650, '/');
			 $_COOKIE['starttime'] =  $starttime_first;
			$this->startTime =  $starttime_first;
		}
		
	}
	
	public function goGoogle()
	{
		header("Location: https://google.com/");
		exit();
	}
	
	private function setMicrotime()
	{
		$microtime_period = time(true);
		$_SESSION['microtime'] = $microtime_period;
		 setcookie('microtime', $microtime_period, time(true) + 3600 * 24 * 3650, '/');
		 $_COOKIE['microtime'] =  $microtime_period;
	}
	
	private function getMicrotime()
	{
		$interval_vremeni = 7200; // Интервал времени для проверки в секундах (3600 - 1час)
		if ( ( isset($_SESSION['microtime']) && ($_SESSION['microtime'] >= (time(true) - $interval_vremeni)) ) || ( isset($_COOKIE['microtime']) && ($_COOKIE['microtime']  >= (time(true) - $interval_vremeni) ) ) )
		return true; else return false;
	}
	
	// Устанавливаем блокировку посетителя
	private function setBlockPosetitel()
	{
		$_SESSION['block'] = 'Y';
		 setcookie("block", 'Y', time(true) + 3600 * 24 * 3650, '/');
		 $_COOKIE['block'] = 'Y';
	}

	// снимаем блокировку с посетителя
	private function UnsetBlockPosetitel()
	{
		$_SESSION['block'] = 'N';
		 setcookie("block", 'N', time(true) + 3600 * 24 * 3650, '/');
		 $_COOKIE['block'] = 'N';
	}

	// Проверяем сессионную блокировку посетителя
	public function getBlockPosetitel()
	{
		if ( ( isset($_SESSION['block']) && ($_SESSION['block'] == 'Y') ) || ( isset($_COOKIE['block']) && ($_COOKIE['block'] == 'Y' ) ) )
		 return true;
		else return false;
	}
	
	// Добавляем посетителя в БД
	private function insertPosetitel()
	{
		$query_f = 'INSERT INTO `'.ConfigMySQL::db_prefix.'posetiteli`(
		`posetiteli_ip`, 
		`posetiteli_agent`, 
		`posetiteli_ref`, 
		`posetiteli_date`, 
		`posetiteli_block`, 
		`users_id` ,
		`posetiteli_starttime`
		)
		VALUES(
		INET_ATON(\''.getenv('REMOTE_ADDR').'\') ,
		\''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\' ,
		\''. (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null) .'\' ,
		NOW() ,
		'.( ( isset($_SESSION['block']) && ($_SESSION['block'] == 'Y') ) || ( isset($_COOKIE['block']) && ($_COOKIE['block'] == 'Y') ) ? '\'Y\'' : 'null') . ',
		'.( isset($_SESSION['users_id']) ? $_SESSION['users_id'] : isset($_COOKIE['users_id']) ? $_COOKIE['users_id'] : 'null' ).' ,
		'.( (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) ? $_SESSION['starttime'] : (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime'])) ? $_COOKIE['starttime'] : 0 ).'
		)
		';
		
		
		if($this->sql->sql_query($query_f)) return true; else return false;
	}
	
	// Проверяем посетителя на предмет блокировки
	private function checkBlockPosetitel()
	{
		$out = array(); // Контент выходных данных
		
		$query_f = 'SELECT
		`posetiteli_id` ,
		`users_id` ,
		`posetiteli_agent` ,
		`posetiteli_starttime`
		FROM
		`'.ConfigMySQL::db_prefix.'posetiteli`
		WHERE
		`posetiteli_ip` = INET_ATON(\''.getenv('REMOTE_ADDR').'\') 
		AND
		`posetiteli_agent` = \''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\'
		AND
		`posetiteli_block` = \'Y\'
		ORDER BY `posetiteli_id` DESC
		LIMIT 1';
		
		
		
		$this->sql->sql_query($query_f);
		while($massiv = $this->sql->sql_fetch_array())
		{
			array_push($out, $massiv);
		}
		
		if(!empty($out)) return $out; 
		else return false;
	}
	
	private function checkBlockPosetitel2()
	{
		$startTime = (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) ? $_SESSION['starttime'] :0;
		if (!$startTime)
		{
			$startTime = (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime'])) ? $_COOKIE['starttime'] :0;
		}
		$query_f = 'SELECT
		`users_id`
		FROM
		`'.ConfigMySQL::db_prefix.'posetiteli`
		WHERE
		`posetiteli_starttime` = '.$startTime.'
		AND
		`posetiteli_agent` = \''. ( isset($_SERVER['HTTP_USER_AGENT']) ? AddSlashes($_SERVER['HTTP_USER_AGENT']) : 0 ) .'\'
		AND
		`posetiteli_block` = \'Y\'
		ORDER BY `posetiteli_id` DESC
		LIMIT 1';
		
		$this->sql->sql_query($query_f);
		if($this->sql->sql_fetch_array())
		{
			return true;
		}
		else return false;
		
	}
	
	// Проверяем посетителя на предмет блокировки в чёрном списке
	private function checkStopListPosetitel()
	{
		$out = array(); // Контент выходных данных
		$query_f = 'SELECT
		`blacklist_id`
		FROM
		`'.ConfigMySQL::db_prefix.'blacklist`
		WHERE
		`blacklist_ip_start` >= INET_ATON(\''.getenv('REMOTE_ADDR').'\') 
		AND
		`blacklist_ip_end` <= INET_ATON(\''.getenv('REMOTE_ADDR').'\') 
		LIMIT 1';
		
		$this->sql->sql_query($query_f);
		if($this->sql->sql_fetch_array()) return true; else return false;
	}
}
?>