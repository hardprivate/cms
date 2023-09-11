<?php
class Model_Main extends Model
{
	// Рандомно выводим пикчи с каталога, ограничение 20 штук
	public function get_data()
	{
		//$data = array();
		
		// Формируем запрос в БД для извлечения рандомных 20 пикч
		return $this->OutputData();
		
		//return $data;
	}
	
	private function OutputData()
	{
		$out = array(); // Контент выходных данных
		
		$gorizontal = 4;
		$vertikal = 5;

		mt_srand(time() + (double) microtime() * 1000000);
		
		$query = "INSERT IGNORE INTO 
			data(data_ojd) 
	VALUES 
		('$data_ojd') 
	ON DUPLICATE KEY UPDATE  data_ojd='$data_ojd'
		
	FROM 
		`".ConfigMySQL::db_prefix."users` 
	WHERE 
		 `video_show` = 'Y'
	ORDER BY rand( ) 
	LIMIT ".intval($gorizontal*$vertikal);
	
	
		$this->sql->sql_query($query);
		while($massiv = $this->sql->sql_fetch_array())
		{
			array_push($out, $massiv);
		}
		return $out;
	}

}
?>