
	</div>
			<h3><?=ConfigHTML::$html_h3;?></h3>
			<?php
				/*
				$i = count(Names::$n_reuestUriArray);
				while (--$i > -1)
				{
					echo Names::$n_reuestUriArray[$i];	
				}
				*/
				
				// Отображаем содержимое главной страницы
				
				// $data массив данных из БД
				
				// Заголовок для катерогии:
				/*
				 if (isset(Names::$n_reuestUriArray[1]) && isset(Names::$n_reuestUriArray[2]) && intval(Names::$n_reuestUriArray[2]))
				 {
					$sql = MysqlEx::_Instance();
					$kategory = Names::$n_reuestUriArray[1];
					$query = "SELECT 
				
				`menu".$kategory."_name`
					
				FROM `".ConfigMySQL::db_prefix."menu".$kategory."` 
				WHERE `menu".$kategory."_show` = 'Y'
				AND `menu".$kategory."_id` = ".intval(Names::$n_reuestUriArray[2])."
				LIMIT 1 
			;";
			
					$sql->sql_query($query);
					if($massiv = $sql->sql_fetch_array())
					{
						echo "<h3>".Names::$n_reuestUriArray[1]." / ".$massiv["menu".$kategory."_name"]."</h3>";
					}
				 }
				 else echo "<h3>".ConfigHTML::$html_h3."</h3>";
				*/
				
				// Список видосов в плиточном варианте
				$j_gorizontal = 2; // Горизонталь
				$k = count($data); // Общее количество видосов, отображаемое на странице
				$i = ceil($k/$j_gorizontal); //Вертикаль
				?><table border="0" width="95%" cellpadding="2" cellspacing="2"><?php
				while (--$i > -1)
				{ 
					?><tr><?php
					$j = $j_gorizontal;
					while (--$j > -1)
					{
						
						?><td  width="384" ><?php
						if (--$k > -1)
						{
						
							//echo 'i = '.$i.'; j = '.$j.'; k = '.$k;
					?>
					<table border="0">
						<tr>
							<td colspan="4">
								
					<a href="/<?=Names::_viewVideo.$data[$k]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
					// echo Names::_dirImages;
					// echo Names::_dirImagesKatalog;
					// echo $data[$k]['video_type'].'/';
					echo $data[$k]['video_linkimg'];
					
					?>"></img></a>
								
							</td>
						</tr>
						<tr style="font-size:150%">
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>download.gif" 
								width="32" height="32" /> 
									<?=$data[$k]['video_download'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>message.gif" 
								width="32" height="32" /> 
									<?=$data[$k]['video_posts'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>like.gif" 
								width="32" height="32" /> 
									<?=$data[$k]['video_like'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>posmotrel.gif" 
								width="32" height="32" /> 
									<?=$data[$k]['video_view'];?> </td>
						</tr>
					</table>
				<?php
						
						}
						else {	echo '&nbsp;'; }
						?></td><?php
					}	
					?></tr><tr><td colspan="<?=$j_gorizontal;?>">&nbsp;</td></tr><?php
					
				
				} ?></table>
				
	</div>
