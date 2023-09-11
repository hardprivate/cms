<?php
class Model_Obratka extends Model
{
	// Обратная связь
	public function get_data()
	{
		$data = array();
		$users_id = $this->Get_users_id();
		// Формируем запрос в БД
		//return $this->OutputData();
		
		// Добавляем комментарий юзера в БД обратки, если он его отправил
		if (isset($_POST['obratka_text']) && strlen($_POST['obratka_text']))
		{
			$this->InsertData($users_id);
		}
		
		// Формирует запрос в БД для извечения всех отправленных юзером комментарий и ответы на них в обратку
		array_push($data, $this->OutputData($users_id) );
		
		return $data;
	}
	
	private function OutputData($users_id)
	{
		$out = array();
		$flag = false;
		$query = "SELECT 
		`obratka_id`, 
		`users_id`, 
		`obratka_title`, 
		`obratka_text`, 
		`obratka_date`, 
		`obratka_kontakt`, 
		`users_id_otvet`, 
		INET_NTOA(`users_ip`) AS users_ip1, 
		`users_agent`, 
		`posetiteli_starttime` 
		FROM `".ConfigMySQL::db_prefix."obratka` 
		WHERE `users_id` = ".$users_id."
		OR `users_id_otvet` = ".$users_id."
		ORDER BY `obratka_date` DESC
		LIMIT 50;
		;";
		
		$this->sql->sql_query($query);
		while($massiv = $this->sql->sql_fetch_array())
		{
			array_push($out, $massiv);
			$flag = true;
		}
		
		return $flag ? $out : null;
	}
	
	private function InsertData($users_id)
	{
		$query = "INSERT INTO `".ConfigMySQL::db_prefix."obratka`(
		`users_id`, 
		`obratka_title`, 
		`obratka_text`, 
		`obratka_date`, 
		`obratka_kontakt`, 
	--	`users_id_otvet`, 
		`users_ip`, 
		`users_agent`, 
		`posetiteli_starttime` 
		) VALUES(
		".$users_id." ,
		'".AddSlashes($_POST['obratka_title'])."' ,
		'".AddSlashes($_POST['obratka_text'])."' ,
		NOW(),
		'".AddSlashes($_POST['obratka_kontakt'])."' ,
		INET_ATON('".getenv('REMOTE_ADDR')."') ,
		'".AddSlashes($_SERVER['HTTP_USER_AGENT'])."' ,
		".$this->GetStartTimeUser()."
		)";
		
		$this->sql->sql_query($query);
	}
	
	private function Get_users_id()
	{
		if (isset($_SESSION['users_id']) && intval($_SESSION['users_id'])) 
			return intval($_SESSION['users_id']); 
		else if (isset($_COOKIE['users_id']) && intval($_COOKIE['users_id']))
			return intval($_COOKIE['users_id']);
		else return 'null';
	}
	
	private function GetStartTimeUser()
	{
		if (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) 
			return intval($_SESSION['starttime']); 
		else if (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime']))
			return intval($_COOKIE['starttime']);
		else return 'null';
	}
	
	
}
?>