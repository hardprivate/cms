
	<div>
		<h3><?=ConfigHTML::$html_h3;?></h3>
			<?php
				// Отображаем видосик на странице
				
				// $data_out массив данных из БД
				
				$data_out0 = $data[0]; // видос
				$data_out1 = $data[1]; // статус видоса
				$data_out2 = $data[2]; // комментарии
				$data_out3 = $data[3]; // 4 случайных превью
				$data_out4 = $data[4]; // реквизиты для доната
				
				
				if (isset($data_out0) && count($data_out0))
				{
				
				 
				
				?>
					<video id="example_video_1" class="video-js vjs-default-skin" preload="metadata" poster="<?php
					echo $data_out0['video_linkimg'];?>" controls width="95%">
					 <source  src="<?php echo Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/content'; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
				
						</video>
					
<br><!--Шапка спойлера-->
<div class="spoilertop" onClick="JavaScript: openClose('pictspoiler','a99', '/images/pictogramma/icon_plu.gif', '/images/pictogramma/icon_min.gif');" 
style="background-Color:#DCDACC;margin:0px; line-height:normal; padding:0px;">
&nbsp;<img id="pictspoiler" src="/images/pictogramma/icon_plu.gif" border="0" width="9" height="9">
<b>Скринлист</b>
</div>
<!--Содержание спойлера-->
<div class="spoilerbox" id="a99" style="display:none;">

               		
								<img
								 src="<?php
								 
								 
					//echo Names::_dirImages;
					//echo Names::_dirImagesKatalog;
					//echo $data_out0['video_type'].'/';
					//echo  substr($data_out0['video_linkimg'], 0, -4).'_scr'.substr($data_out0['video_linkimg'], -4);
					echo $data_out0['video_linkimg_scr'];
					?>"
								 
								 border='0'
								 width='95%' ><br>


</div>
<!--Конец спойлера-->
					
				<?php
					//	Поддержи видос <a href="https://money.yandex.ru/to/410015380260095" target="_blank"><b>донатом</b></a>
					//<br>
					//<iframe src="https://money.yandex.ru/quickpay/shop-widget?writer=seller&targets=donates&targets-hint=donates&default-sum=5&button-text=11&payment-type-choice=on&mobile-payment-type-choice=on&hint=&successURL=https%3A%2F%2F<?=$_SERVER['HTTP_HOST'];? >%2F<?php echo Names::$n_reuestUriArray[0].'%2F'.Names::$n_reuestUriArray[1].'%2F'.Names::$n_reuestUriArray[2].'%2Fdonat'; ? >&quickpay=shop&account=410015380260095" width="423" height="222" frameborder="0" allowtransparency="true" scrolling="no"></iframe>
				
					//закомменченый видос поднять на строку выше (вынести из скрипта)
					// Адрес на который возвращается юзер после платежа
					// https://hardprivate.com/ok
				} ?>
				<br>
				<form action="<?php echo Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/posts'; ?>" method="post">
					<table border="0">
				
								<tr style="font-size:230%">
							<td><?
									echo '<a href="'.Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/mTorrent" >';
							?><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>mTorrent<?=intval($data_out1['mTorrent']);?>.gif" 
								width="64" height="64" /><?php 
								echo '</a>'; ?> 
									<?=$data_out0['video_mTorrent'];?> &nbsp; </td>
									
									
							<td><?
									echo '<a href="'.Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/download" >';
							?><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>download<?=intval($data_out1['download']);?>.gif" 
								width="64" height="64" /><?php 
								echo '</a>'; ?> 
									<?=$data_out0['video_download'];?> &nbsp; </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>message<?=intval($data_out1['posts']);?>.gif" 
								width="64" height="64" /> 
									<?=$data_out0['video_posts'];?> &nbsp; </td>
									
									
							<td><?php
							if(!$data_out1['like'])
							{
								echo '<a href="'.Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/like" >';
							}else
							{
								echo '<a href="'.Names::$n_reuestUriArray[0].'/'.Names::$n_reuestUriArray[1].'/'.Names::$n_reuestUriArray[2].'/unlike" >';
							}
							?><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>like<?=intval($data_out1['like']);?>.gif" 
								width="64" height="64" /><?php 
								//if(!$data_out1['like'])
								echo '</a>'; ?> 
									<?=$data_out0['video_like'];?> &nbsp; </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>posmotrel<?=intval($data_out1['view']);?>.gif" 
								width="64" height="64" /> 
									<?=$data_out0['video_view'];?> &nbsp; </td>
						</tr>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>
						
									
						<?php
						if ($data_out4)
						{
							?>
							<tr>
								<td colspan="5">&nbsp;<br>
									
									
						
									<center>///////////////////
									
									

<a class="myLinkModal" href="#" onclick="copytext('#copytext1')"><b>Donate</b></a>

<div id="myModal">
  <p><b>Donate:</b> <br> <?=$data_out4['donat_type'];?> <br><span id="copytext1"><?=$data_out4['donat_score'];?></span></p>
  <span id="myModal__close" class="close">ₓ</span>
</div>
<div id="myOverlay"></div>


									
									///////////////////</center>
									
									
						
							
								<br>&nbsp;</td>
							</tr>
							
					


							<!--
							<tr>
								<td colspan="4"><b>Donate:</b> <br> <?//=$data_out4['donat_type'];?> <br> <?//=$data_out4['donat_score'];?> </td>
							</tr>
							<tr>
							<td colspan="4">&nbsp;</td>
							</tr>
							//-->
							<?php
						}
						?>
						<tr>
							<td colspan="5">
								<span id="komment"> Откомментировать можно сюда:</span><br> 
								<textarea wrap="0" name="videoposts_text" maxlength="254"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<input type="submit" name="msg_submit" value="Добавить комментарий">
							</td>
						</tr>
					</table>
				</form>
					
				<table border="0" cellpadding="5" cellspacing="5">
					
				
<?php
	// Вывод тексковых комментарий пользователей
	
	//$data_out2 = $data[2];
	if (isset($data_out2) && $data_out2 && count($data_out2))
	foreach($data_out2 as $row)
	{
		$posetiteli_starttime = (isset($row['posetiteli_starttime'])) ?  $row['posetiteli_starttime'] : '';
		if (isset($row['users_ip1']) )
		{
			$users_ip_start = substr($row['users_ip1'], 0, strpos($row['users_ip1'], '.'));
			
		} else
		{
			$users_ip_start = '';
		}
		
		echo '<tr><td colspan="3">&nbsp;</td></tr>';
		echo '<tr><td class="users_ip_start"><b>'.$posetiteli_starttime.'-'.$users_ip_start.'</b></td><td class="users_ip_start_probel"></td><td class="users_ip_date"><i>'.$row['videoposts_date'].'</i></td></tr>';
		echo '<tr><td colspan="3">'.$row['videoposts_text'].'</td></tr>';
	}
?>
				</table>
			<br>	
<?php 				
		
		

		// Список видосов в плиточном варианте
		
				$j_gorizontal = 2; // Горизонталь
				$k = count($data_out3); // Общее количество видосов, отображаемое на странице
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
								
					<a href="/<?=Names::_viewVideo.$data_out3[$k]['video_id'];
					?>"><img width="384" src="<?php
					
					// Изображение пикчи
					// echo Names::_dirImages;
					// echo Names::_dirImagesKatalog;
					// echo $data_out3[$k]['video_type'].'/';
					echo $data_out3[$k]['video_linkimg'];
					
					?>"></img></a>
								
							</td>
						</tr>
						<tr style="font-size:150%">
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>download.gif" 
								width="32" height="32" /> 
									<?=$data_out3[$k]['video_download'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>message.gif" 
								width="32" height="32" /> 
									<?=$data_out3[$k]['video_posts'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>like.gif" 
								width="32" height="32" /> 
									<?=$data_out3[$k]['video_like'];?> </td>
							<td><img src="<?=Names::_dirImages.Names::_dirImagesIcon;?>posmotrel.gif" 
								width="32" height="32" /> 
									<?=$data_out3[$k]['video_view'];?> </td>
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
<br/>
