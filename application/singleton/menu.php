<?php
// Меню навигации
class MenuLeft
{
	private static $_instance;
	private $sql;

	public function __construct()
	{
		$this->sql = MysqlEx::_Instance();
	}
	// Шаблон одиночка
	public static function _Instance() 
	{
		if (!isset(self::$_instance)) 
		{
			self::$_instance = new MenuLeft();
		}
		return self::$_instance;
	}
	
	// Сгенерированное меню в HTML формате из БД.
	public function menuLeftHTML() 
	{
		return $this->menuLeftString($this->menuLeftQuery());
	}
	
	// Извлекаем данные из БД и возвращаем массив сырых элементов
	private function menuLeftQuery() 
	{
		$out = array(); // Контент выходных данных
		$query_f = 'SELECT 
		--	`menu_id` , 
			`menu_name` , 
			`menu_link` , 
		--	`menu_dostup`,
			`menu_target` ,
			`menu_kategory`
				FROM 
			`'.ConfigMySQL::db_prefix.'menu` 
				WHERE `menu_show` = \'Y\'
				ORDER BY `menu_sort` ASC
				, `menu_id` DESC, `menu_name` DESC
				;';

		$this->sql->sql_query($query_f);
		while($massiv = $this->sql->sql_fetch_array())
		{
			array_push($out, $massiv);
		}
		return $out;
	}
	
	// Формируем строковое значение меню из полученного массива
	private function menuLeftString($data)
	{
		$i = count($data);
		$s = '';
		while (--$i > -1)
		{
			if(isset($data[$i]['menu_kategory']) && (strlen($data[$i]['menu_kategory']) > 0))
			{
				$s .= '<li><a name="'.$data[$i]['menu_kategory'].'" style="cursor:pointer" onclick="doMenu(\'sidebar_'.$data[$i]['menu_kategory'].'\');">'.$data[$i]['menu_name'].'</a></li> ';
				$s .= $this->Menu_Kategory($data[$i]['menu_kategory']);
			}
			else
			$s .= '<li><a href="/'.$data[$i]['menu_link'].'" target="'.$data[$i]['menu_target'].'">'.$data[$i]['menu_name'].'</a></li> ';
		}
		return $s;
	}
	
	private function Menu_Kategory($kategory)
	{
		if(isset($kategory) && (strlen($kategory) >0))
		{
			
			$s = ''; // Контент выходных данных подменю
			
			
			$s .= '<div id="sidebar_'.$kategory.'">';
			$s .= '		<span class="side-box-kategory">';	
			$s .= '			<ul class="list-kategory">';
			
			
			if(($kategory == 'hd') || ($kategory == 'dvd'))
				$s .= $this->SelectQuery_hd_dvd($kategory);
			if ($kategory == 'story')
				$s .= $this->SelectQuery_story($kategory);
			
			
			$s .= '			</ul>';
			$s .= '		</span>';	
			$s .= '</div>';
			
			return $s;
		} 
		else return '';
		
	}
	
	private function SelectQuery_hd_dvd($kategory)
	{
		$s = '';
		
			$query = "SELECT 
				`menu".$kategory."_id`, 
				`menu".$kategory."_name`, 
			
				`menu".$kategory."_sort`, 
				`menu".$kategory."_show`
			
				FROM `".ConfigMySQL::db_prefix."menu".$kategory."` 
				WHERE `menu".$kategory."_show` = 'Y'
				ORDER BY `menu".$kategory."_sort` ASC, `menu".$kategory."_name` ASC, `menu".$kategory."_id` ASC
			;";
			
			$this->sql->sql_query($query);
			while($massiv = $this->sql->sql_fetch_array())
			{
				$s .= '<li><a class="sidebar_'.$kategory.'" href="/'.$kategory.'/'.$massiv["menu".$kategory."_id"].'" target="_self">'.$massiv["menu".$kategory."_name"].'</a></li> ';
			}
			
		return $s;
	}
	
	private function SelectQuery_story($kategory)
	{
		$s = '';
		
		$query = 'SELECT
			
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat`,
			`'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat_link`
		FROM `'.ConfigMySQL::db_prefix.'storykat`  
		WHERE 1
		ORDER BY `'.ConfigMySQL::db_prefix.'storykat`.`storykat_kat` ASC 
		; ';
			
			$this->sql->sql_query($query);
			while($massiv = $this->sql->sql_fetch_array())
			{
				$s .= '<li><a class="sidebar_'.$kategory.'" href="/'.$kategory.'/'.$massiv['storykat_kat_link'].'" target="_self">'.$massiv['storykat_kat'].'</a></li> ';
			}
			
		return $s;
	}

	

}
?>