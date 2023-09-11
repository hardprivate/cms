<?php
class Model_Photo extends Model
{
	// Рандомно выводим пикчи с каталога, ограничение 20 штук
	public function get_data()
	{
		$data = array();
		
		// Заголовок для страницы записываем в статичные переменные
		//$this->HTML_H3();
				
		// Формируем запрос в БД для извлечения рандомных 20 пикч
		array_push($data, $this->OutputData() );
		
		return $data;
	}
	
	private function OutputData()
	{
		/*
			$query = 'SELECT 
			`donat_id`, 
			`donat_type`, 
			`donat_score`, 
			`donat_show` 
			FROM `'.ConfigMySQL::db_prefix.'donat` 
			WHERE `donat_show` = \'Y\' 
			ORDER BY rand() LIMIT 1';
			
		$this->sql->sql_query($query);
			//while($massiv = $this->sql->sql_fetch_array())
			if($massiv = $this->sql->sql_fetch_array())
			{
				return $massiv;
			}
			else 
			*/
			return false;
	}


}
?>