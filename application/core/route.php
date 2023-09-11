<?php
// Маршрутизация
class Route
{
	static function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = 'main';
		$action_name = 'index';
		$parametr = 1;

		/*
		В элементе глобального массива $_SERVER['REQUEST_URI'] содержится полный адрес по которому обратился пользователь.
		Например: example.ru/contacts/feedback

		С помощью функции explode производится разделение адреса на составлющие. 
		В результате мы получаем имя контроллера, для приведенного примера, 
		это контроллер contacts и имя действия, в нашем случае — feedback.
 
		*/
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);
		Names::$n_reuestUriArray = $routes;

		// получаем имя контроллера
		if ( !empty($routes[1]) )
		{	
			switch($routes[1])
			{
				case 'hd':$routes[1] = 'main'; break;
				case 'dvd':$routes[1] = 'main'; break;
				
			}
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		// Получаем имя параметра вместо имени экшэна
		/*if ( !empty($routes[2]) )
		{
			//$action_name = $routes[2];
			$parametr = $routes[2];
		}*/
		
/* Далее подключается файл модели (модель может отсутствовать) и файл контроллера, если таковые имеются и наконец, создается экземпляр контроллера и вызывается действие, опять же, если оно было описано в классе контроллера.

Таким образом, при переходе, к примеру, по адресу:
example.com/portfolio
 или
example.com/portfolio/index
 роутер выполнит следующие действия:
подключит файл model_portfolio.php из папки models, содержащий класс Model_Portfolio;
подключит файл controller_portfolio.php из папки controllers, содержащий класс Controller_Portfolio;
создаст экземпляр класса Controller_Portfolio и вызовет действие по умолчанию — action_index, описанное в нем. */
		

		// добавляем префиксы
		$model_name = 'Model_'.ucfirst($controller_name);
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)

		// $model_file = strtolower($model_name).'.php';
		if(isset($model_file) && (strlen($model_file) > 0))
		$model_file .= $model_name.'.php';
		else $model_file = $model_name.'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path))
		{
			include "application/models/".$model_file;
		}
		else
		{
			//echo 'Файл не существует '.$model_path;
			self::ErrorPage404();
			exit(0);
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "application/controllers/".$controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			self::ErrorPage404();
		}
		
		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			if(count($routes) > 3)
			{
				$controller->$action(array_slice($routes, 3));
			}
			else
			{
				// вызываем действие контроллера
				$controller->$action();
			}
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			self::ErrorPage404();
		}
	
	}
	
	public static function ErrorPage404()
	{
        $host = '/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
    public static function ErrorPage403()
    {
        $host = '/';
        header('HTTP/1.1 403 Forbidden');
	header("Status: 403 Forbidden");
	//header('Location:'.$host.'403');
    }
}
?>