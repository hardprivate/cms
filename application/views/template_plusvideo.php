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
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		
		
		<script src="/js/menu.js" type="text/javascript"></script>
		<script type="text/javascript" language="JavaScript1.1" defer src="/js/video_type.js"></script>


		<link rel="stylesheet" type="text/css" href="/css/style_plusvideo.css" />

		
	</head>
	<body onload="doKategoryTypeHD()">
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
		<div id="footer">
			Публикацию новых видосов стимулируют <a href="https://money.yandex.ru/to/410015380260095" target="_blank"><b>донаты</b></a>
			<br>
			The publication of new videos is stimulated by <a href="https://money.yandex.ru/to/410015380260095" target="_blank"><b>donations</b></a>
		</div>
	</body>
</html>