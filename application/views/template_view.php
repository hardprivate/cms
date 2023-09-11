<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 3.0 License

Name       : Accumen
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20120712

Modified by VitalySwipe
-->
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=<?=ConfigHTML::$charset;?>" />
		<meta name="description" content="<?=ConfigHTML::$description;?>" />
		<meta name="keywords" content="<?=ConfigHTML::$keywords;?>" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		
		<?php

		
		// css
		ConfigHTML::$css_src_array = array_reverse(ConfigHTML::$css_src_array);
		$i = count(ConfigHTML::$css_src_array);
		while (--$i > -1)
		{
			?> 
				<link rel="stylesheet" type="text/css" href="<?=ConfigHTML::$css_src_array[$i];?>" /> 
			<?php
		}
		
		ConfigHTML::$script_src_array = array_reverse(ConfigHTML::$script_src_array);
		// script
		$i = count(ConfigHTML::$script_src_array);
		while (--$i > -1)
		{
			?> 
				<script src="<?=ConfigHTML::$script_src_array[$i];?>" type="text/javascript"></script> 
			<?php
		}
		?>
		
		<title><?=ConfigHTML::$html_title;?><?=(strlen(ConfigHTML::$html_h3_subkat))?ConfigHTML::$html_h3_subkat:ConfigHTML::$html_h3;?></title>
		
		<!--script src="/js/menu.js" type="text/javascript"></script//-->
		<!--script type="text/javascript" language="JavaScript1.1" defer src="/js/spoilers_scr.js"></script//-->


		<!--link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" /-->
		<!--link href="http://fonts.googleapis.com/css?family=Kreon" rel="stylesheet" type="text/css" /-->
		<!--link rel="stylesheet" type="text/css" href="/css/style.css" /-->
		<!-- script src="/js/jquery-1.6.2.js" type="text/javascript"></script //-->
		<!-- script type="text/javascript">
		// return a random integer between 0 and number
		function random(number) {
			
			return Math.floor( Math.random()*(number+1) );
		};
		
		// show random quote
		$(document).ready(function() { 

			var quotes = $('.quote');
			quotes.hide();
			
			var qlen = quotes.length; //document.write( random(qlen-1) );
			$( '.quote:eq(' + random(qlen-1) + ')' ).show(); //tag:eq(1)
		});
		</script //-->
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="logo">
					<span>
						
						<a href="/" target="_self"><img src="/images/girl_logo.gif" width="86px" height="110px" /></a>
						<a href="/" target="_self">HardPrivate.com</a>
					</span>
				</div>
				<?
				/* <!-- div id="menu">
					<ul>
						<li><a href="/">Главная</a></li>
						<li><a href="/services">Услуги</a></li>
						<li><a href="/guestbook">Гостевая книга</a></li>
						<li><a href="/contacts">Контакты</a></li>
					</ul>
					<br class="clearfix" />
				 </div //-->
				*/
				?>
			</div>
			<div id="page">
				<div id="img_menu_slider">
					<img  src="/images/menu.jpg" width="124" height="100"; onclick="doMenu('sidebar');" onmouseover="doMenuOver('img_menu_slider');" onmouseout="doMenuOut('img_menu_slider');" />
				</div>

				<div id="sidebar">
					<span class="side-box">
						<!-- h3>Основное меню</h3 //-->
						<ul class="list">
							<?php
							// Меню навигации
							$menu = MenuLeft::_Instance();
							echo $menu->menuLeftHTML();
							?>
						</ul>
					</span>
				</div>
				<div id="content">
					<div class="box">
						<?php include 'application/views/'.$content_view; ?>
					</div>
					<!-- br class="clearfix" //-->
				</div>
				<!-- br class="clearfix" //-->
			</div>

			<div id="page-bottom">

			</div>
		</div>
		<div id="footer"><small>
		    [o] - obkonchennaya;
		    [k] - konchil v pisku;</small>
		<!--	Публикацию новых видосов стимулируют <a href="https://money.yandex.ru/to/410015380260095" target="_blank"><b>донаты</b></a> (Яндекс-деньги: 410015380260095)
			<br>
			The publication of new videos is stimulated by <a href="https://money.yandex.ru/to/410015380260095" target="_blank"><b>donations</b></a> (Yandex-money: 410015380260095)
		//--></div>
	</body>
</html>