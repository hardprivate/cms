<h2>Обратная связь</h2>
Центр сбора ваших пожеланий и предложений<br><br>
	<form action="<?=$_SERVER['REQUEST_URI'];?>"  method="post">
		
		Тема письма: <br> <input type="text" name="obratka_title" maxlength="100" />
			<br>
		Текст письма: <br> <textarea name="obratka_text" maxlength="254"></textarea>
			<br>
		Координаты для ответа (не обязательно) :<br>  <input type="text" name="obratka_kontakt" maxlength="100" size="45"/>
		<br><br>
		<input type="submit" value="Отправить письмо">
	</form>
	
	
		
	<table border="0" cellpadding="5" cellspacing="5">
<?php

	// Вывод тексковых комментарий пользователя, а так же ответы на них
	
	if (isset($data[0]) && count($data[0]))
	foreach($data[0] as $row)
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
		echo '<tr><td class="users_ip_start"><b>'.$posetiteli_starttime.'-'.$users_ip_start.'</b></td><td class="users_ip_start_probel">'.$row['users_id'].'</td><td class="users_ip_date"><i>'.$row['obratka_date'].'</i></td></tr>';
		echo '<tr><td colspan="3">'.$row['obratka_title'].'</td>';
		echo '<tr><td colspan="3">'.$row['obratka_text'].'</td>';
		echo '<tr><td colspan="3">'.$row['obratka_kontakt'].'</td>';
	}
	else
	{
		echo '<tr><td colspan="3">&nbsp;</td></tr>';
	}
?>
	</table>
<br>
