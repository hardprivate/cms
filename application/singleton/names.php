<?php
class Names
{
	public static $n_reuestUriArray = array(); // Массив содержащий данные из строки адреса
	// 0 Хост new.avtos.su
	// 1 Имя контроллера
	// 2 Имя экшена
	public static $n_tmpDataOutput = ''; // Строковые данные для переброса данных между моделью и отображением
	
	// Страница, содержащая видео на странице
	const _viewVideo = 'video/';

	// Каталог с изображениями
	const _dirImages = '/images/';
	// Подкаталог с изображениями пикчей с каталога видео
	const _dirImagesKatalog = 'katalog/';
	// Подкаталог с пиктограммами
	const _dirImagesIcon = 'icon/';
	
	
}
?>