	<div>
		<h3><?=ConfigHTML::$html_h3;?></h3>
<?php
			$story = $data[0][0]; // контент выходных данных рассказ или список рассказов
			$flag = $data[1]['flag']; // флаг: рассказ или список рассказов
			$story_list = $data[2];  // список рассказов в данной категории
			$storykat_kat = $data[3]; // список категорий внизу рассказа
			
			if(($flag == 0) || ($flag == 1))
			{
				// Покажет рассказ
				echo $story['story_text'].' <br>&nbsp; ';
				
				// Покажет категории
				$i = count($storykat_kat);
				while (--$i > -1)
					{
				echo " <b><a href=\"".'/'.Names::$n_reuestUriArray[1].'/'.$storykat_kat[$i]['storykat_kat_link']."\">".$storykat_kat[$i]['storykat_kat']."</a></b> ";
					}
				echo ' <br>&nbsp; ';
				
			}
				
			if($flag == 2)
			{
				// Покажет ссылки на другие рассказы из этой категории
				$i = count($story_list);
				//$story_list = array_reverse($story_list); переворачивает массив (реализовано в запросе БД)
				if($i)
				{
?>
				<table  border="0">
<?php 
					while (--$i > -1)
					{
?>
					<tr>
						<td><a href="<?php 
						
						echo Names::$n_reuestUriArray[0].'/';
						echo Names::$n_reuestUriArray[1].'/';
						echo $story_list[$i]['storykat_kat_link'].'/';
						echo $story_list[$i]['story_link'].'/';
						echo $story_list[$i]['story_id']; 
						
						?>"><?=$story_list[$i]['story_name']?></a></td>
					</tr>
<?php
					}
?>
				</table>
<?php
				}
			}
?>		
		
	</div>