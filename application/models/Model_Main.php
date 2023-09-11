<?php
class Model_Main extends Model
{
	// Рандомно выводим пикчи с каталога, ограничение 20 штук
	public function get_data()
	{
		//$data = array();
		
		// Заголовок для страницы записываем в статичные переменные
		$this->HTML_H3();
				
		// Формируем запрос в БД для извлечения рандомных 20 пикч
		return $this->OutputData();
		
		//return $data;
	}
	
	private function HTML_H3()
	{
			 if (isset(Names::$n_reuestUriArray[1]) && isset(Names::$n_reuestUriArray[2]) && intval(Names::$n_reuestUriArray[2]))
				 {
					$sql = MysqlEx::_Instance();
					$kategory = Names::$n_reuestUriArray[1];
					$query = 'SELECT 
				
				`menu'.$kategory.'_name`
					
				FROM `'.ConfigMySQL::db_prefix.'menu'.$kategory.'` 
				WHERE `menu'.$kategory.'_show` = \'Y\'
				AND `menu'.$kategory.'_id` = '.intval(Names::$n_reuestUriArray[2]).'
				LIMIT 1 
			;';
			
					$sql->sql_query($query);
					if($massiv = $sql->sql_fetch_array())
					{
						ConfigHTML::$html_h3 = Names::$n_reuestUriArray[1].' / '.$massiv['menu'.$kategory.'_name'];
						ConfigHTML::$html_h3_subkat = $massiv['menu'.$kategory.'_name'];
					}
				 }
				 
	}
	
	private function OutputData()
	{
		$out = array(); // Контент выходных данных
		
		
		mt_srand(time() + (double) microtime() * 1000000);
		
		$query = "SELECT
		 `".ConfigMySQL::db_prefix."video`.`video_id` , 
		 `".ConfigMySQL::db_prefix."video`.`video_name` , 
		  `".ConfigMySQL::db_prefix."video`.`videosubdir_id` AS videosubdir_id ,
		 `".ConfigMySQL::db_prefix."video`.`video_link` , 
		 `".ConfigMySQL::db_prefix."video`.`video_linkimg` , 
	--	 `".ConfigMySQL::db_prefix."video`.`video_kategory` , 
		 `".ConfigMySQL::db_prefix."video`.`video_posts` , 
		 `".ConfigMySQL::db_prefix."video`.`video_like` , 
		 `".ConfigMySQL::db_prefix."video`.`video_view` ,
		 `".ConfigMySQL::db_prefix."video`.`video_download` ,
		 `".ConfigMySQL::db_prefix."video`.`video_dobavlen` , 
		 `".ConfigMySQL::db_prefix."video`.`video_show` ,
		 `".ConfigMySQL::db_prefix."video`.`video_type` ,
		 
		 `".ConfigMySQL::db_prefix."videodisck`.`videodisck_name` AS videodisck_name ,
		 `".ConfigMySQL::db_prefix."videodir`.`videodir_dir` AS videodir_dir ,
		 `".ConfigMySQL::db_prefix."videosubdir`.`videosubdir_dir` AS videosubdir_dir
	FROM 
		`".ConfigMySQL::db_prefix."video` 
	LEFT JOIN `".ConfigMySQL::db_prefix."videodisck`  USING( `videodisck_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videodir`  USING( `videodir_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videosubdir`  USING( `videosubdir_id` )
	
	WHERE 
		 `video_show` = 'Y' ";
		 
		 if (isset(Names::$n_reuestUriArray[1]) && isset(Names::$n_reuestUriArray[2]))
		 {
		 $query .= (Names::$n_reuestUriArray[1] == 'hd') ? " AND `video_type` = 'hd' AND `kategory_id` = ".intval(Names::$n_reuestUriArray[2]):"";
		 $query .= (Names::$n_reuestUriArray[1] == 'dvd') ? " AND `video_type` = 'dvd' AND `kategory_id` = ".intval(Names::$n_reuestUriArray[2]):"";
		 }
		 
		 if (!isset(Names::$n_reuestUriArray[1]) || ((Names::$n_reuestUriArray[1] != 'hd') && (Names::$n_reuestUriArray[1] != 'dvd')))
		 {
			$query .= " AND `video_showmain` = 'Y' ORDER BY rand( ) ";
			$query .= " LIMIT 20 ; ";
		 }
		 else
		 {
		 
			$query .= " LIMIT 100 ; ";
		 }
		$this->sql->sql_query($query);
		while($massiv = $this->sql->sql_fetch_array())
		{
			$this->MassivLink($massiv);
			array_push($out, $massiv);
		}
		return $out;
	}
	
	
	
	private function MassivLink(&$massiv)
	{

		// Преобразуем ссылку на видео на локальный сервер
				$massiv['video_link'] = 'https://'.$_SERVER['HTTP_HOST'].'/video/'.
					$massiv['videodisck_name'] . '/' . $massiv['video_type'] . '/' .
					$massiv['videodir_dir'] . '/';
				$massiv['video_link_abs'] = '/home/gt/html/ha0/public_html/video/'.
					$massiv['videodisck_name'] . '/' . $massiv['video_type'] . '/' .
					$massiv['videodir_dir'] . '/';
				if ($massiv['videosubdir_id'] > 0)
				{
					$massiv['video_link'] .= $this->sql->myUrlEncode($massiv['videosubdir_dir']) . '/';
					$massiv['video_link_abs'] .= $massiv['videosubdir_dir'] . '/';
				}
		// Картинки скринов на локальный сервер:
				$massiv['video_linkimg_scr'] = $massiv['video_link'] . $massiv['video_name'].'_scr.jpg';
		// Картинка постера на локальный сервер:
				if (isset($massiv['video_linkimg']))
				{
					$massiv['video_linkimg'] = $massiv['video_link'] . substr ( $massiv['video_linkimg'] , strpos($massiv['video_linkimg'], $massiv['video_name']) );
				}
				else
				{
					$massiv['video_linkimg'] = $massiv['video_link'] . $massiv['video_name'].'.jpg';
				}
				$massiv['video_link'] .= $massiv['video_name'].'.mp4';
				$massiv['video_link_abs'] .= $massiv['video_name'].'.mp4';
				//var_dump($massiv['video_link_abs']);
				
				// Принимает переменную по ссылке, поэтому возвращать нет смысла
				//return $massiv; 
	}

}
?>