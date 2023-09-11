<div>
	plusvideo
	<?php
	if (isset($data['sovpadenie']) && is_array($data['sovpadenie']))
	{
	echo '<br> Найдены совпадения : <br>';
	
						?>
					<table border="0">
					<?php
					$i = count($data['sovpadenie']);
					while (--$i > -1)	
					{
					?>	<tr>
							<td>
					<?=$data['sovpadenie'][$i]['video_type'].'/'.$data['sovpadenie'][$i]['video_linkimg'];?>
					<br>
								
					<a target="_blank" href="/<?=Names::_viewVideo.$data['sovpadenie'][$i]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
				//	echo Names::_dirImages;
				//	echo Names::_dirImagesKatalog;
				//	echo $data['sovpadenie'][$i]['video_type'].'/';
					echo $data['sovpadenie'][$i]['video_linkimg'];
					
					?>"></img></a>
							</td>
						
							<td>
							Скрин: 
					<?php
					//$cs_video_linkimg = substr($data_out0['sovpadenie'][$i]['video_linkimg'], 0, -4).'_cs'.substr($data_out0['sovpadenie'][$i]['video_linkimg'], -4);
					?>
					 <?/*$data['sovpadenie'][$i]['video_type'].'/'. $cs_video_linkimg;*/?>
					<br>
								
					<a target="_blank" href="/<?=Names::_viewVideo.$data['sovpadenie'][$i]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
					
					echo $data['sovpadenie'][$i]['video_linkimg_scr'];
					
					
					//echo Names::_dirImages;
					//echo Names::_dirImagesKatalog;
					//echo $data['sovpadenie'][$i]['video_type'].'/';
					//echo  $cs_video_linkimg;
					
					?>"></img></a>
					 
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					<?php
					}
					?>
					</table>
	<?php
	}
	?>					
	<br/>
		<form action="<?=$_SERVER['REQUEST_URI'];?>" method="post">
			Поиск файла [video_name_old] <input type="text" name="video_name_old" 
			value="<?php
				if(isset($_POST['video_name_old']) && strlen($_POST['video_name_old']))
				echo $_POST['video_name_old'];
				?>"
			/> <br/>
			
			<input type="submit" name="search_file" value="Поиск файла" />
		
		</form>
	
	<br/><br/>
	
		<form action="<?=$_SERVER['REQUEST_URI'];?>" method="post">
			Новая категория:
			<select name="video_type">
				<option value="hd" selected>hd</option>
				<option value="dvd">dvd</option>
			</select>
	
			Show<select name="menu_type_show">
				<option value="Y" selected>Y</option>
				<option value="N">N</option>
			</select>

			<br/>
			<input type="text" name="menu_type_name" />
			<br/>
			Сортировать:<br>
			<input type="text" name="menu_type_sort" />
			<br>
			Level:<br>
			<input type="text" name="menu_type_level" />
			
			<input type="submit" name="plus_kat_submit" value="Добавить категорию" />
		
		</form>
	
		<br/>
		Новое видео
		<form action="<?=$_SERVER['REQUEST_URI'];?>" method="post">
			
			<select name="video_type" id="video_type_select">
				<option value="hd" onclick="doKategoryTypeHD()"  <?php
						if ($data['lastinsertvideo'][0]['video_type'] == "hd") echo "selected"; ?>> hd</option>
				<option value="dvd"onclick="doKategoryTypeDVD()"  <?php
						if ($data['lastinsertvideo'][0]['video_type'] == "dvd") echo "selected"; ?>>dvd</option>
			</select>
			
			<?php 
			if(isset($data['video_hd_kategory']) && is_array($data['video_hd_kategory']))
			{
			?>
				<span id="video_hd_kategory">
				<select name="menuhd">
				<?php
					$i = count($data['video_hd_kategory']);
					while (--$i > -1)	
					{
					?>
						<option value="<?=$data['video_hd_kategory'][$i]['menuhd_id']?>" <?php
						if(($data['lastinsertvideo'][0]['video_type'] == "hd") && 
							($data['lastinsertvideo'][0]['kategory_id'] == $data['video_hd_kategory'][$i]['menuhd_id']) ) echo "selected";
						?>><?=$data['video_hd_kategory'][$i]['menuhd_name']?>
						|level <?=$data['video_hd_kategory'][$i]['menuhd_level']?>
						|show <?=$data['video_hd_kategory'][$i]['menuhd_show']?>
						|sort <?=$data['video_hd_kategory'][$i]['menuhd_sort']?></option>
					<?	
					}
					?>
				</select>
				</span>
			<?php
			}
			?>
			
			<?php 
			if(isset($data['video_dvd_kategory']) && is_array($data['video_dvd_kategory']))
			{
			?>
				<span id="video_dvd_kategory">
				<select name="menudvd">
				<?php
					$i = count($data['video_dvd_kategory']);
					while (--$i > -1)	
					{
					?>
						<option value="<?=$data['video_dvd_kategory'][$i]['menudvd_id']?>" <?php
						if(($data['lastinsertvideo'][0]['video_type'] == "dvd") && 
							($data['lastinsertvideo'][0]['kategory_id'] == $data['video_dvd_kategory'][$i]['menudvd_id']) ) echo "selected";
						?>><?=$data['video_dvd_kategory'][$i]['menudvd_name']?>
						|level <?=$data['video_dvd_kategory'][$i]['menudvd_level']?>
						|show <?=$data['video_dvd_kategory'][$i]['menudvd_show']?>
						|sort <?=$data['video_dvd_kategory'][$i]['menudvd_sort']?></option>
					<?	
					}
					?>
				</select>
				</span>
			<?php
			}
			?>
			
			
			<br/>
			
			Совпадения по значению video_name_old<br>
			<select name="sovpadenie" >
				<option value="show_only">Показать, не вставляя новое, не очищать форму</option>
				<option value="show_only_clear">Показать, не вставляя новое, и очистить форму</option>
				<option value="show_insert">Показать или вставить новое, не очищать форму</option>
				<option selected value="show_insert_claer">Показать или вставить новое и очистить форму</option>
				<option value="insert_clear">Вставить новое и очистить форму</option>
			</select>
			<br>
			
			Имя переименнованного видеофайла [video_name] <input type="text" name="video_name" /> <br/>
			Первоначальное имя видеофайла [video_name_old] <input type="text" name="video_name_old" /> <br/>
			На каком hdd находится видеофайл [video_from] <input type="text" name="video_from" /> <br/>
			<select name="video_link_type" >
				<option value="local">local</option>
				<option selected value="global">global</option>
			</select>
			DropBox ссылка на видеофайл [video_link] <input type="text" name="video_link" /> <br/>
			ссылка изображения на видеофайл  [video_linkimg]<input type="text" name="video_linkimg" /> <br/>
			ссылка скринлиста на видеофайл  [video_linkimg_scr]<input type="text" name="video_linkimg_scr" /> <br/>
			<br/>
			Публиковать: <select name="video_show">
				<option selected value="1">Да</option>
				<option value="0">Нет</option>
			</select>
			Цензура: <select name="video_csenzura">
				<option selected value="1">Да</option>
				<option value="0">Нет</option>
			 </select>
			На главной: <select name="video_showmain">
				<option selected value="1">Да</option>
				<option value="0">Нет</option>
			 </select>
			Level: <select name="video_level">
				<option value="0">0</option>
				<option selected value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			 </select>
			<input type="submit" name="plus_video_submit" value="Добавить видео" />
		
		</form>

<br> Last Insert Video
	<?php
	if (isset($data['lastinsertvideo']) && is_array($data['lastinsertvideo']))
	{
	echo '<br> Последнее вставлнное в базу видео : <br>';
	
						?>
					<table border="0">
					<?php
					$i = count($data['lastinsertvideo']);
					while (--$i > -1)	
					{
					?>	<tr>
							<td valign="top">
					<?/*$data['lastinsertvideo'][$i]['video_type'].'/'.$data['lastinsertvideo'][$i]['video_linkimg'];*/?>
					
				<?	
					
					if (isset($data['lastinsertvideo'][$i]) && count($data['lastinsertvideo'][$i]))
				{
					// Заголовок для катерогии:
				 if (isset($data['lastinsertvideo'][$i]['video_type']) && isset($data['lastinsertvideo'][$i]['kategory_id']))
				 {
					$sql = MysqlEx::_Instance();
					$kategory = $data['lastinsertvideo'][$i]['video_type'];
					$query = "SELECT 
				
				`menu".$kategory."_name`
					
				FROM `".ConfigMySQL::db_prefix."menu".$kategory."` 
				WHERE `menu".$kategory."_id` = ".$data['lastinsertvideo'][$i]['kategory_id']."
				LIMIT 1 
			;";
			
					$sql->sql_query($query);
					if($massiv = $sql->sql_fetch_array())
					{
						echo "<h3><a href=\"/".$data['lastinsertvideo'][$i]['video_type']."/".$data['lastinsertvideo'][$i]['kategory_id']."\">".$data['lastinsertvideo'][$i]['video_type']." / ".$massiv["menu".$kategory."_name"]."</a></h3>";
					}
				 }		
				}
				?>	
					
					<br>
						<b>video_name</b><br>
						<?=$data['lastinsertvideo'][$i]['video_name'];?>
					
					<br>
						&nbsp;	
					<br>
						<b>video_name_old</b><br>
						<?=$data['lastinsertvideo'][$i]['video_name_old'];?>
					
					<br>
						&nbsp;	
					<br>
						<b>video_link</b><br>
						<?=$data['lastinsertvideo'][$i]['video_link'];?>
					
						
					<br>
						&nbsp;
					<br>
								
					<a target="_blank" href="/<?=Names::_viewVideo.$data['lastinsertvideo'][$i]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
				//	echo Names::_dirImages;
				//	echo Names::_dirImagesKatalog;
				//	echo $data['lastinsertvideo'][$i]['video_type'].'/';
					echo $data['lastinsertvideo'][$i]['video_linkimg'];
					
					?>"></img></a>
							</td>
						
							<td>
							Скрин: 
					<?php
				//	$cs_video_linkimg = substr($data_out0['lastinsertvideo'][$i]['video_linkimg'], 0, -4).'_cs'.substr($data_out0['lastinsertvideo'][$i]['video_linkimg'], -4);
					?>
					 <?/*$data['lastinsertvideo'][$i]['video_type'].'/'. $cs_video_linkimg;*/?>
					<br>
								
					<a target="_blank" href="/<?=Names::_viewVideo.$data['lastinsertvideo'][$i]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
					
					echo $data['lastinsertvideo'][$i]['video_linkimg_scr'];
					
				//	echo Names::_dirImages;
				//	echo Names::_dirImagesKatalog;
				//	echo $data['lastinsertvideo'][$i]['video_type'].'/';
				//	echo  $cs_video_linkimg;
					
					?>"></img></a>
					 
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					<?php
					}
					?>
					</table>
	<?php
	}
?>

</div>
	<br>