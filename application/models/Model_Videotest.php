<?php
class Model_Videotest extends Model
{

	private $user;
	private $viewArr = array();

	// Преобразует айди видоса в ссылку на видос
	public function get_data()
	{
		$this->user = CheckPosetitelAuth::_Instance();
		$data = array();
		// Формируем запрос в БД для извлечения ссылки на видос
		$videoOut = $this->OutputVideo();
		// Заголовок для страницы записываем в статичные переменные
		$this->HTML_H3($videoOut);
		// видеоплеер на странице
		$this->PlayerJS();
		
		
		$nameArr = array('content','donat', 'view', 'unlike', 'like' , 'download', 'posts');
		
		// Проверяем валидность/доступность страницы с видеофайлом
		if ($this->CheckIssetVideo($videoOut))
		{
		
			$i = count($nameArr);
			while (--$i > -1)
			{
				$this->Plus_1($nameArr[$i], $this->SearchElement($nameArr[$i]));
			}
			
			//$videoOut = $this->OutputVideo();
		
			// Добавляем первый элемент в массив, ранее: Формируем запрос в БД для извлечения ссылки на видос
			array_push($data, $videoOut );
		
			// Добавляем в передаваемый массив статус посмотренного/лайкнутого/откомментированного/загруженного
			array_push($data, $this->viewArr );
		
			// Формирует запрос в БД для извечения комментарий пользователей к видео
			array_push($data, $this->OutputVideoComment() );
			
			// 4 случайных превьюшек видео
			array_push($data, $this->OutputRandom4() );
		
			// Выдаёт ссылку для загрузки видео юзеру
			//var_dump($videoOut['video_link']);
			// && file_exists($videoOut['video_link'])
			// if(isset($videoOut['video_link'])  ) $this->StreamerVideo($videoOut['video_link']);
			//if (isset($videoOut['video_link']) ) $this->VideoSetLinkDowload($videoOut['video_link']);
			if (isset($videoOut['video_link']) && is_file($videoOut['video_link_abs']) ) $this->VideoSetLinkDowload($videoOut);
		}
		return $data;
	}
	
	/////////////////////////////////////////////////////////////////////////////
	//плеер на странице
	
	private function PlayerJS()
	{
		// ConfigHTML::$script_src_array;
		// ConfigHTML::$css_src_array;
		// array_push(ConfigHTML::$css_src_array, '/css/video-js.min.css');
		// array_push(ConfigHTML::$script_src_array, '/js/video.min.js');
	}
	
	/////////////////////////////////////////////////////////////////////////////
	// заголовок для страницы
	private function HTML_H3($data_out0)
	{
		if (isset($data_out0) && count($data_out0))
		{
					// Заголовок для катерогии:
				
				 if (isset($data_out0['video_type']) && isset($data_out0['kategory_id']))
				 {
					$sql = MysqlEx::_Instance();
					$kategory = $data_out0['video_type'];
					$query = "SELECT 
				
				`menu".$kategory."_name`
					
				FROM `".ConfigMySQL::db_prefix."menu".$kategory."` 
				WHERE `menu".$kategory."_id` = ".$data_out0['kategory_id']."
				LIMIT 1 
			;";
			
					$sql->sql_query($query);
					if($massiv = $sql->sql_fetch_array())
					{
						ConfigHTML::$html_h3 =
						"<a href=\"/".$data_out0['video_type']."/".$data_out0['kategory_id']."\">".$data_out0['video_type']." / ".$massiv["menu".$kategory."_name"]."</a>";
						ConfigHTML::$html_h3_subkat = $massiv["menu".$kategory."_name"];
						
					}
				 }				
	
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////
	
		// Загрузка видоса с сервака на комп юзером
		
	private function VideoSetLinkDowload($massivSQL)
	{
		if (isset(Names::$n_reuestUriArray[3]) && ((Names::$n_reuestUriArray[3] == 'content')))  //|| (Names::$n_reuestUriArray[3] == 'download')) )
		{
			//header('Content-type: video/mp4');
			//header('Content-Disposition: attachment; filename="'.$massivSQL['video_id'].'.mp4"');
			//header('Transfer-Encoding: chunked');
			//header("Location: ".$massivSQL['video_link']);
			
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/v.php?v='.$massivSQL['video_id']);
			
			//echo $this->my_stream_get_contents ($video_link);
			//$this->stream_mp4($video_link);
			//echo file_get_contents(  $video_link  );
		}
		
		
		if (isset(Names::$n_reuestUriArray[3]) && (Names::$n_reuestUriArray[3] == 'download') )
		{
			header('Content-type: video/mp4');
			header('Content-Disposition: attachment; filename="'.$massivSQL['video_id'].'.mp4"');
			//header('Transfer-Encoding: chunked');
			header("Location: ".$massivSQL['video_link']);
		
			//header('Location: https://'.$_SERVER['HTTP_HOST'].'/v.php?v='.$massivSQL['video_id']);
		
		/*
		//var_dump($massivSQL['video_link_abs']);
header('Content-type: video/mp4');
header('Content-type: video/mpeg');
header('Content-type: application/octet-stream'); 
//echo mime_content_type($massivSQL['video_link_abs']);
header('Content-disposition: inline');
header("Content-Transfer-Encoding: binary");
header('Content-Disposition: attachment; filename="'.$massivSQL['video_id'].'.mp4"');
//header("Content-Length: ".filesize($massivSQL['video_link_abs']));

$handle = fopen($massivSQL['video_link_abs'], "rb") or die("Couldn't get handle");
if ($handle) {
    while (!feof($handle)) {
        echo fgets($handle, 4096);
        // Process buffer here..
    }
    fclose($handle);
}
exit(0);
		*/	
		}
		
		
		
	}
	
	function stream_mp4($file)
	{
	//require 'vendor/autoload.php';

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($file);
//$video
//    ->filters()
//    ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
//    ->synchronize();
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
    ->save('frame.jpg');
$video
    ->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4');
//    ->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
//    ->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
	}
	
	
	
	
	function my_stream_get_contents ($file, $timeout_seconds = 0.5)
{
	$handle = fopen($file, 'r'); // truncate
    $ret = "";
    // feof ALSO BLOCKS:
    // while(!feof($handle)){$ret.=stream_get_contents($handle,1);}
    while (true) {
        $starttime = microtime(true);
        $new = stream_get_contents($handle, 1);
        $endtime = microtime(true);
        if (is_string($new) && strlen($new) >= 1) {
            $ret .= $new;
        }
        $time_used = $endtime - $starttime;
        // var_dump('time_used:',$time_used);
        if (($time_used >= $timeout_seconds) || ! is_string($new) ||
                 (is_string($new) && strlen($new) < 1)) {
            break;
        }
    }
    return $ret;
}

	
	/////////////////////////////////////////////////////////////////////////////
	
		// Фрагментальная отдача видео
	/*	
		
	private function StreamerVideo($file) // принимает параметр абсолютного адреса на видос
	{
		if (isset(Names::$n_reuestUriArray[3]) && ((Names::$n_reuestUriArray[3] == 'content') || (Names::$n_reuestUriArray[3] == 'download') ))
		{
		//$file = 'video360p.mp4';
$fp = @fopen($file, 'rb');
$size = filesize($file); // File size
$length = $size; // Content length
$start = 0; // Start byte
$end = $size - 1; // End byte
header('Content-type: video/mp4');
//header("Accept-Ranges: 0-$length");
header("Accept-Ranges: bytes");
if (isset($_SERVER['HTTP_RANGE'])) {
	$c_start = $start;
	$c_end = $end;
	list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
	if (strpos($range, ',') !== false) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		header("Content-Range: bytes $start-$end/$size");
		exit;
	}
	
	if ($range == '-') {
		$c_start = $size - substr($range, 1);
	}else{
		$range = explode('-', $range);
		$c_start = $range[0];
		$c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
	}
	$c_end = ($c_end > $end) ? $end : $c_end;
	
	if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		header("Content-Range: bytes $start-$end/$size");
		exit;
	}
	$start = $c_start;
	$end = $c_end;
	$length = $end - $start + 1;
	fseek($fp, $start);
	header('HTTP/1.1 206 Partial Content');
}
header("Content-Range: bytes $start-$end/$size");
header("Content-Length: ".$length);
$buffer = 1024 * 8;
while(!feof($fp) && ($p = ftell($fp)) <= $end) {
	if ($p + $buffer > $end) {
		$buffer = $end - $p + 1;
	}
	set_time_limit(0);
	echo fread($fp, $buffer);
	flush();
}
fclose($fp);
exit();
		}
	}
	
	*/
	/////////////////////////////////////////////////////////////////////////////
	
	// Проверяем валидность/доступность страницы с видеофайлом
	private function CheckIssetVideo($videoOut)
	{
		if (isset($videoOut) && is_array($videoOut) && count($videoOut))
		{
			if (intval(Names::$n_reuestUriArray[2]) != $videoOut['video_id']) 
			{
				header("Location: /");
				exit();
			}
			else return true;
		} else 
		{
			header("Location: /");
			exit();
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////
	
		//Статистика просмотра, лайков, дизлайков, скачивания и комментирования
	
	/////////////////////////////////////////////////////////////////////////////
	//private $nameArr = array('view', 'like' , 'unlike', 'download', 'posts');
	
	// Поиск элемента в массиве
	private function SearchElement($name)
	{
	
		$search_flag = false;
		// Записываем в переменную массив элемента видоса
		$search_arr = array();
		$arr_tmp = $this->user->getUsersArr('users_'.$name);
				
		if (isset($arr_tmp))
		{
			if (is_array($arr_tmp)) $search_arr = $arr_tmp;
			else if((strlen($arr_tmp)) > 0 && (intval($arr_tmp) > 0)) $search_arr[] = intval($arr_tmp);
		
		
			$i = count($search_arr);
			while (--$i > -1)
			{
				// поиск в массиве из БД юзера идентификатора видоса посмотренных видосов
				if ($search_arr[$i] == Names::$n_reuestUriArray[2])
				{
					$search_flag = true;
					break;
				}
			}
		}
		return $search_flag;
	}
	
	// Добавление +1 к статистике просмотра, лайка, скачивания, комментирование видоса
	private function Plus_1($name, $flag)
	{
		$this->viewArr[$name] = $flag;
		$unlike_flag = ($name == 'unlike') ? true:false;
		if(( ($name == 'view') || (isset(Names::$n_reuestUriArray[3]) && (Names::$n_reuestUriArray[3] == $name))  ) && (!$flag || (($name == 'posts') && (isset(Names::$n_reuestUriArray[3]) && (Names::$n_reuestUriArray[3] == 'posts')) && isset($_POST['videoposts_text'])) ) && !($unlike_flag && !$this->viewArr['like']) )
		{
			$query_arr = array();
			if ($unlike_flag) $name = 'like';
			// Записываем в переменную массив всех посмотренных видосов и добавляем новый элемент
			$plus_1_arr = $this->user->getUsersId('users_'.$name);
			
			if (!$unlike_flag)
			{
				// Добавили элемент в массив
				if (isset($plus_1_arr) && (strlen($plus_1_arr) > 0))
					$plus_1_arr .= ',' . Names::$n_reuestUriArray[2];
				else $plus_1_arr = Names::$n_reuestUriArray[2];
			
				 
			// В случае дизлайка, удаляем элемент из массива
			}
			else
			{
				if (isset($plus_1_arr) && (strlen($plus_1_arr) > 0))
				{
				$plus_1_arr = explode(',', $plus_1_arr);
				$i = count($plus_1_arr);
				while (--$i > -1)
				{
					// Удаляем элемент массива при его нахождении в нём
					if ($plus_1_arr[$i] == Names::$n_reuestUriArray[2])
					{
						unset($plus_1_arr[$i]);
						if (count($plus_1_arr) > 0) $plus_1_arr = implode(',' , $plus_1_arr);
						else $plus_1_arr = '';
						break;
					}
				}
				}
			}
			
			if ($plus_1_arr === '')
				$plus_1_string = 'null';
			else $plus_1_string = "'".$plus_1_arr."'";
			
			if ($name != 'content')
			{
			$query_arr[] = "UPDATE `".ConfigMySQL::db_prefix."users` 
			SET `users_".$name."` = ".$plus_1_string."
			WHERE`users_id` = ".$this->user->getUsersId('users_id')."
			LIMIT 1 ; ";
			}
			
			$query_arr[] = "UPDATE `".ConfigMySQL::db_prefix."video` 
			SET `video_".$name."` = `video_".$name."` ".( ($unlike_flag && $this->viewArr['like']) ? " - " : " + " )." 1
			WHERE `video_id` = ".Names::$n_reuestUriArray[2]."
			LIMIT 1 ; ";
			
			$query_arr[] = "INSERT INTO `".ConfigMySQL::db_prefix."video".$name."`  (
			`video_id`, 
			`users_id`, 
			`video".$name."_date` 
			".( ($unlike_flag) ? " , `videolike_YN` " : "" )." 
			".( ( ($name == 'posts') && isset($_POST['videoposts_text']) ) ? ", `users_ip`, `users_agent`, `posetiteli_starttime`, `videoposts_text` " : "" )."
			) VALUES(
			".Names::$n_reuestUriArray[2]." ,
			".((($this->user->getUsersId('users_id') !== null) && intval($this->user->getUsersId('users_id'))) ? $this->user->getUsersId('users_id') : 0)." ,
			NOW() 
			".( ($unlike_flag) ? " , 'minus' " : "" )." 
			".( ( ($name == 'posts') && isset($_POST['videoposts_text']) ) ? (" , INET_ATON('".getenv('REMOTE_ADDR')."') , '".AddSlashes($_SERVER['HTTP_USER_AGENT'])."' , ".$this->GetStartTimeUser()." ,'".AddSlashes($_POST['videoposts_text'])."'") : "" )."
			) ; ";
			
			$i = count($query_arr);
			while (--$i > -1)
			{
				$this->sql->sql_query($query_arr[$i]);
			}
			
			if (($name === 'like') || (isset(Names::$n_reuestUriArray[3]) && ((Names::$n_reuestUriArray[3] === 'donat') || ( (Names::$n_reuestUriArray[3] === 'posts') && isset($_POST['videoposts_text']) ) ) ) )
			header("Location: ".Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2]);
			
			//if ($name === 'download')
			//header("Location: ".Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/content');
		}
	}
	
	
	
	private function GetStartTimeUser()
	{
		if (isset($_SESSION['starttime']) && intval($_SESSION['starttime'])) 
			return intval($_SESSION['starttime']); 
		else if (isset($_COOKIE['starttime']) && intval($_COOKIE['starttime']))
			return intval($_COOKIE['starttime']);
		else return 'null';
	}
	
	
	/////////////////////////////////////////////////////////////////////////////
	
		// Отображает видеоряд
		
	/////////////////////////////////////////////////////////////////////////////
	
	// Извлекает из БД данные о видео для дальнейшего вывода его на страницу
	private function OutputVideo()
	{
		//$out = array(); // Контент выходных данных

		if (isset(Names::$n_reuestUriArray[2]) && intval(Names::$n_reuestUriArray[2]))
		{
		
		$query = "SELECT
		 `".ConfigMySQL::db_prefix."video`.`video_id` AS video_id , 
		 `".ConfigMySQL::db_prefix."video`.`video_name` AS video_name ,
		 `".ConfigMySQL::db_prefix."video`.`videosubdir_id` AS videosubdir_id ,
		 `".ConfigMySQL::db_prefix."video`.`video_link` AS video_link, 
		 `".ConfigMySQL::db_prefix."video`.`video_linkimg` AS video_linkimg , 
		 `".ConfigMySQL::db_prefix."video`.`video_linkimg_scr` AS video_linkimg_scr , 
		 `".ConfigMySQL::db_prefix."video`.`video_kategory` AS video_kategory , 
		 `".ConfigMySQL::db_prefix."video`.`kategory_id` AS kategory_id , 
		 `".ConfigMySQL::db_prefix."video`.`video_posts` AS video_posts , 
		 `".ConfigMySQL::db_prefix."video`.`video_like` AS video_like , 
		 `".ConfigMySQL::db_prefix."video`.`video_view` AS video_view ,
		 `".ConfigMySQL::db_prefix."video`.`video_download` AS video_download ,
		 `".ConfigMySQL::db_prefix."video`.`video_dobavlen` AS video_dobavlen , 
		 `".ConfigMySQL::db_prefix."video`.`video_show` AS video_show ,
		 `".ConfigMySQL::db_prefix."video`.`video_type` AS video_type ,
		 
		 `".ConfigMySQL::db_prefix."videodisck`.`videodisck_name` AS videodisck_name ,
		 `".ConfigMySQL::db_prefix."videodir`.`videodir_dir` AS videodir_dir ,
		 `".ConfigMySQL::db_prefix."videosubdir`.`videosubdir_dir` AS videosubdir_dir
	FROM 
		`".ConfigMySQL::db_prefix."video` 
	LEFT JOIN `".ConfigMySQL::db_prefix."videodisck`  USING( `videodisck_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videodir`  USING( `videodir_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videosubdir`  USING( `videosubdir_id` )
	
	WHERE 
		 `video_show` = 'Y'
		 AND
		 `video_id`= ".Names::$n_reuestUriArray[2]."
	LIMIT 1;";
	
			$this->sql->sql_query($query);
			//while($massiv = $this->sql->sql_fetch_array())
			if($massiv = $this->sql->sql_fetch_array())
			{
				//array_push($out, $massiv);
				
				// Преобразуем ссылку на видео на локальный сервер
				$this->MassivLink($massiv);
				return $massiv;
			}
			else return false;
		
		//return $out;
		}
		else return false;
	}
	
	/*//////////////////////////////////////////////////////////////////////////////
	
					Сетка превьюшек под комментами
	
	//////////////////////////////////////////////////////////////////////////////*/
	
	private function OutputRandom4()
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
		 
		 
		 
			$query .= " ORDER BY rand( )  LIMIT 8 ; ";
		 
		$this->sql->sql_query($query);
		while($massiv = $this->sql->sql_fetch_array())
		{
			$this->MassivLink($massiv);
			array_push($out, $massiv);
		}
		return $out;
	}
	
	/*//////////////////////////////////////////////////////////////////////////////
	
				Перелинковка для адресов контента: картинок и видео на локальный хост
	
	//////////////////////////////////////////////////////////////////////////////*/
	
	
	
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
	
	/////////////////////////////////////////////////////////////////////////////////////
	
	// Извлекает из БД все комменты к данному видео
	private function OutputVideoComment()
	{
		$out = array(); // Контент выходных данных

		if (isset(Names::$n_reuestUriArray[2]) && intval(Names::$n_reuestUriArray[2]))
		{
		
		$query = "SELECT
			`videoposts_id`,
			`video_id`
			`users_id`, 
			`videoposts_text`, 
			`videoposts_date`, 
			`videoposts_like`, 
			`users_id_otvet` ,
			INET_NTOA(`users_ip`) AS users_ip1, 
			`users_agent`, 
			`posetiteli_starttime`
	FROM 
		`".ConfigMySQL::db_prefix."videoposts` 
	WHERE 
		 `video_id`= ".Names::$n_reuestUriArray[2]."
		AND
		 `videoposts_hidden` = 'N'
	ORDER BY `videoposts_date` DESC
	LIMIT 20;";
	
			
			$this->sql->sql_query($query);
			while($massiv = $this->sql->sql_fetch_array())
			{
				array_push($out, $massiv);
			}
		
		return $out;
		}
		else return false;	
	}

}
?>