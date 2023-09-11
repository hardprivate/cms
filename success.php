<div>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$filename = 'success.txt';
	$fd = fopen($filename, 'a') or die("не удалось создать файл");
	$date_today = date("m.d.Y");
	$time_now = date("H:i:s");
	$str = '<br /><br />';
	$str .= $date_today .' '. $time_now . ' ' . getenv('REMOTE_ADDR') . ' ' . getenv('REMOTE_HOST'). '<br />';
	
	foreach ($_POST as $key => $value) 
	{
		$str .= $key .'=>'. $value .'<br />';
	}
	
	$str .= '<br /><br />';
	fwrite($fd, $str);
	fclose($fd);
	echo 'ok';
}
?>
</div>