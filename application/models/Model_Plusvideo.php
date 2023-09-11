<?php
class Model_Plusvideo extends Model
{
	// Обратная связь
	
	private $data = array();
	
	public function get_data()
	{
		// Извлекаем категории из БД
		$this->SelectKategoryList('hd');
		$this->SelectKategoryList('dvd');
		$this->LastInsertVideo();
		
		// Вставка новой категории
		if (isset($_POST['plus_kat_submit']) && strlen($_POST['menu_type_name']))
		{
			if(!$this->SearchKategory(AddSlashes($_POST['video_type']))) $this->InsertKategory();
		}
	
		// Поиск совпадений в БД
		if (isset($_POST['search_file'])) $this->SearchSovpadeniya();
		
		// Вставка нового видео в БД
		if (isset($_POST['plus_video_submit'])) $this->RouteInsert();
		
		return $this->data;
	}
	
	private function HL_redirect()
	{
		header("Location: ".Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2]);
	}
	
	private function LastInsertVideo()
	{
		$out = array();
		$query = "SELECT
		 `video_id` ,
		 `video_type` ,
		 `video_linkimg` ,
		 `video_linkimg_scr` ,
		 `video_type` ,
		 `kategory_id` ,
		 `video_name` ,
		 `video_name_old` ,
		 `video_link`
	FROM 
		`".ConfigMySQL::db_prefix."video` 
	WHERE  1
		ORDER BY  `video_id` DESC
	LIMIT 1;";
	
			$this->sql->sql_query($query);
			
			while($massiv = $this->sql->sql_fetch_array())
			{
				array_push($out, $massiv);
			}
			
		if (count($out))
		{ 
			$this->data['lastinsertvideo'] = $out;
			return true;
		}
		else return false;
	}
	
	private function InsertKategory()
	{
		$type = AddSlashes($_POST['video_type']);
		$query = "INSERT INTO `".ConfigMySQL::db_prefix."menu".$type."`  (
		 `menu".$type."_name`, 
		 `menu".$type."_sort`, 
		 `menu".$type."_show`, 
		 `menu".$type."_level`
			 ) VALUES(
		'".AddSlashes($_POST['menu_type_name'])."' ,
		".((isset($_POST['menu_type_sort'])  && intval($_POST['menu_type_sort'])) ? ("'".intval($_POST['menu_type_sort'])."'") : "NULL")." ,
		'".AddSlashes($_POST['menu_type_show'])."' ,
		'".intval($_POST['menu_type_level'])."' 
			 ) ; " ;
		 
		 $this->sql->sql_query($query);
		 
		 $this->HL_redirect();
	}
	
	private function SearchKategory($type = 'hd')
	{
		$query = "SELECT
		 `menu".$type."_id`, 
		 `menu".$type."_name`, 
		 `menu".$type."_sort`, 
		 `menu".$type."_show`, 
		 `menu".$type."_level`
	FROM 
		`".ConfigMySQL::db_prefix."menu".$type."` 
	WHERE `menu".$type."_name` = '".AddSlashes($_POST['menu_type_name'])."'  LIMIT 1;";
	
			$this->sql->sql_query($query);
			
			if($massiv = $this->sql->sql_fetch_array())
			{
				return true;
			}
			else return false;
			
	}
	
	private function SelectKategoryList($type = 'hd')
	{
		$out = array();
		$query = "SELECT
		 `menu".$type."_id`, 
		 `menu".$type."_name`, 
		 `menu".$type."_sort`, 
		 `menu".$type."_show`, 
		 `menu".$type."_level`
	FROM 
		`".ConfigMySQL::db_prefix."menu".$type."` 
	WHERE 1 ORDER BY `menu".$type."_name` DESC;";
	
			$this->sql->sql_query($query);
			
			while($massiv = $this->sql->sql_fetch_array())
			{
				array_push($out, $massiv);
			}
			
		if (count($out))
		{ 
			$this->data['video_'.$type.'_kategory'] = $out;
			return true;
		}
		else return false;
	}
	
	private function RouteInsert()
	{
		switch($_POST['sovpadenie'])
		{
			case 'show_only':
				$this->SearchSovpadeniya();
				$this->data['form_clear'] = false;
				break;
			case 'show_only_clear':
				$this->SearchSovpadeniya();
				$this->data['form_clear'] = true;
				break;
			case 'show_insert':
				if (!$this->SearchSovpadeniya()) $this->InsertNewVideo();
				$this->data['form_clear'] = false;
				break;
			case 'show_insert_claer':
				if (!$this->SearchSovpadeniya()) 
				{
					$this->InsertNewVideo();
					$this->data['form_clear'] = true;
				}
				else $this->data['form_clear'] = false;
				break;
			case 'insert_clear':
				$this->InsertNewVideo();
				$this->data['form_clear'] = true;
				break;
			default: 
				if (!$this->SearchSovpadeniya())
				{
					$this->InsertNewVideo();
					$this->data['form_clear'] = true;
				}
				else $this->data['form_clear'] = false;
		}
	}
	
	private function SearchSovpadeniya()
	{
		$out = array();
		$query = "SELECT
		 `video_id` ,
		 `video_type` ,
		 `video_linkimg` ,
		 `video_linkimg_scr`
	FROM 
		`".ConfigMySQL::db_prefix."video` 
	WHERE 
		 `video_name_old` LIKE '%".AddSlashes($_POST['video_name_old'])."%' 
	LIMIT 10;";
	
			$this->sql->sql_query($query);
			
			while($massiv = $this->sql->sql_fetch_array())
			{
				array_push($out, $massiv);
			}
			
		if (count($out))
		{ 
			$this->data['sovpadenie'] = $out;
			return true;
		}
		else return false;
	}
	
	private function InsertNewVideo()
	{
		if ($_POST['video_type'] == 'dvd')
		{
			$kategory_id = $_POST['menudvd'];
		}
		else $kategory_id = $_POST['menuhd'];
		
		$query = "INSERT INTO `".ConfigMySQL::db_prefix."video` (
		 `video_name`, 
		 `video_name_old`, 
		 `video_from`, 
		 `video_link`, 
		 `video_link_type` ,
		 `video_linkimg`, 
		 `video_linkimg_scr`,
	--	 `video_kategory`, 
		 `kategory_id`, 
	--	 `video_posts`, 
	--	 `video_like`, 
	--	 `video_view`, 
	--	 `video_download`, 
	--	 `video_donat`, 
		 `video_dobavlen`, 
		 `video_show`, 
		 `video_type`, 
		 `video_csenzura`, 
		 `video_showmain` ,
		 `video_level`
		 ) VALUES(
		'".AddSlashes($_POST['video_name'])."' ,
		'".AddSlashes($_POST['video_name_old'])."' ,
		'".AddSlashes($_POST['video_from'])."' ,
		'".AddSlashes($_POST['video_link'])."' ,
		'".AddSlashes($_POST['video_link_type'])."' ,
		'".AddSlashes($_POST['video_linkimg'])."' ,
		'".AddSlashes($_POST['video_linkimg_scr'])."' ,
		'".AddSlashes($kategory_id)."' ,
		NOW() ,
		".(intval($_POST['video_show']) ? "'Y'"  : "NULL" )." ,
		'".AddSlashes($_POST['video_type'])."' ,
		".(intval($_POST['video_csenzura']) ? "'Y'"  : "NULL" )." ,
		".(intval($_POST['video_showmain']) ? "'Y'"  : "NULL" )." ,
		".intval($_POST['video_level'])." 
		 ) ; " ;
		 
		 $this->sql->sql_query($query);
		 
		 $this->HL_redirect();
	}
}
?>