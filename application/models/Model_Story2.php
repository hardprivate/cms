<?php
class Model_Story extends Model
{
	// Сказска 18+
	
	private $flag = 0;
	private $storykat_kat_link = '0';
	
	public function get_data()
	{
		// Возвращаемый массив данных для отображения
		$data1 = array();
		//$data = array();
				
		$data = $this->OutputData($this->SelectSQL_elemet($this->Parse_uri()));
		// Если данные есть, то возвращаем их, иначе запрашиваем рандомный рассказ
		if (!count($data)) 
		{
			$this->flag = 0;
			$data = $this->OutputData($this->SelectSQL_elemet($this->flag));
		}
		// var_dump($data);
		$this->storykat_kat_link = $data[0]['storykat_kat_link'];
		$this->HTML_H3($data[0]);
		
		
		// Упаковываем перед отправкой
		array_push($data1, $data, array('flag' => $this->flag), $this->OutputData($this->SelectSQL_elemet(2)),  $this->OutputData($this->SelectSQL_kat()) ); 
		return $data1;
	}
	
	private function SelectSQL_kat()
	{
		$query = 'SELECT
			
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat`,
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat_link`
		FROM `'.ConfigMySQL::db_prefix.'storykat`  
		WHERE 1
		ORDER BY  rand( ) 
		; ';
		
		return $query;
	}
	
	//Заголовок
	private function HTML_H3($massiv_sql_result)
	{
		switch($this->flag)
		{
			case 2: // список рассказов
				ConfigHTML::$html_h3 = $massiv_sql_result['storykat_kat'];
				break;
			default:
				//0 или 1 случайный рассказ
				ConfigHTML::$html_h3 = 
					"<a href=\"".'/'.Names::$n_reuestUriArray[1].'/'.$massiv_sql_result['storykat_kat_link']."\">".$massiv_sql_result['storykat_kat']."</a> / ".$massiv_sql_result['story_name'];
				ConfigHTML::$html_h3_subkat = $massiv_sql_result['story_name'];
		}
		//ConfigHTML::$html_h3 = ;
		//ConfigHTML::$html_h3_subkat = ;
	}
	
	private function Parse_uri()
	{
		//Names::$n_reuestUriArray[2] - категория, список рассказов в данной категории
		// при условии что длина строки больше двух символов и не является числовым значением
		
		// $flag = 0;
		
		if (isset(Names::$n_reuestUriArray[3]) && 
			(strlen(Names::$n_reuestUriArray[3]) > 2) &&
			isset(Names::$n_reuestUriArray[4]) && 
			(intval(Names::$n_reuestUriArray[4]) > 2)
			)
		{
			$this->flag = 1; // рассказ
		}
		elseif(isset(Names::$n_reuestUriArray[2]) && strlen(Names::$n_reuestUriArray[2]) > 2)
		{
			$this->flag = 2; // список рассказов
		}
		// else $flag = 0; случайный рассказ
		
		return $this->flag;
	}
	
	// Заапрашивает список рассказов в категории
	// Запрашивает элемент, непосредственно - сам рассказ
	private function SelectSQL_elemet($flag = 0)
	{
		// Флаг определяет:
		// 0 - покажет рандомный рассказ
		// 1 - запрошен сам элемент, т.е. рассказ
		// 2 - запрошены заголовки из рассказов в данной категории
		
		$query_select = '';
		$query_where = ' 1 ';
		$query_order_by = ' `'.ConfigMySQL::db_prefix.'story`.`story_name` DESC ';
		$query_limit = ' 1 ';
		
		$storykat_kat_link = (isset(Names::$n_reuestUriArray[2])) ? Names::$n_reuestUriArray[2] : $this->storykat_kat_link;
		
		switch($flag)
		{
			case 1: 
				$query_select = ' `'.ConfigMySQL::db_prefix.'story`.`story_text`, ';
				$query_where = ' `'.ConfigMySQL::db_prefix.'story`.`story_id` = '.intval(Names::$n_reuestUriArray[4]); 
				$query_where .= ' AND ';
				$query_where .= ' `'.ConfigMySQL::db_prefix.'story`.`story_link` = \''.addslashes(Names::$n_reuestUriArray[3]).'\' '; 
				break;
			case 2:
				$query_where = ' `'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat_link` = \''.addslashes($storykat_kat_link).'\' ';
				$query_limit = ' 500 ';
				break;
			default: 
			//0
			$query_select = ' `'.ConfigMySQL::db_prefix.'story`.`story_text`, ';
			$query_order_by = ' rand( ) ';
		}
		
		
		
		$query = 'SELECT
			`'.ConfigMySQL::db_prefix.'story`.`story_id`,
			`'.ConfigMySQL::db_prefix.'story`.`story_name`,
			`'.ConfigMySQL::db_prefix.'story`.`story_kat`,
	--		`'.ConfigMySQL::db_prefix.'story`.`story_avtor`,
	--		`'.ConfigMySQL::db_prefix.'story`.`story_vstuplenie`,
			
			'.$query_select.'
			
	--		`'.ConfigMySQL::db_prefix.'story`.`story_text`,
	--		`'.ConfigMySQL::db_prefix.'story`.`story_data`,
			`'.ConfigMySQL::db_prefix.'story`.`story_link` ,
			
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat`,
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat_link`
	--		count(`'.ConfigMySQL::db_prefix.'story`.`story_kat`) AS count_story
		FROM
			`'.ConfigMySQL::db_prefix.'story`
		LEFT JOIN `'.ConfigMySQL::db_prefix.'storykat`  
			ON  `'.ConfigMySQL::db_prefix.'story`.`story_kat` = `'.ConfigMySQL::db_prefix.'storykat`.`storykat_id`
		WHERE '.$query_where.'
	--	GROUP BY `'.ConfigMySQL::db_prefix.'story`.`story_kat`
		ORDER BY '.$query_order_by.'
		LIMIT '.$query_limit;
		
		return $query;
	}
	
	private function OutputData($query)
	{
		$out = array(); // Контент выходных данных
	
		$this->sql->sql_query($query);
		while($massiv = $this->sql->sql_fetch_array())
		{
			array_push($out, $massiv);
		}
		//$count_out = count($out);
		return $out;
	}

}
?>