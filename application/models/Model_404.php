<?php
class Model_404 extends Model
{
	// Рандомно выводим пикчи с каталога, ограничение 20 штук
	public function get_data()
	{
		$data = array();
		
		// Заголовок для страницы записываем в статичные переменные
		//$this->HTML_H3();
				
		// Формируем запрос в БД для извлечения рандомных 20 пикч
		//return $this->OutputData();
		
		header("Location: /");
		return $data;
	}
	


}
?>