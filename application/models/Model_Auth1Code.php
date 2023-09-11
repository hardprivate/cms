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
		
		$query = "SELECT
		 `video_id` , 
		 `video_name` , 
		 `video_link` , 
		 `video_linkimg` , 
		 `video_kategory` , 
		 `video_posts` , 
		 `video_like` , 
		 `video_view` ,
		 `video_download` ,
		 `video_dobavlen` , 
		 `video_show`
	FROM 
		`".ConfigMySQL::db_prefix."video` 
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