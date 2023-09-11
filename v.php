<?php
// Редиректит видео для загрузки/просмотра
if (version_compare(PHP_VERSION, '7.0.0','>=')) include 'mysql.php';
// require_once('vendor/autoload.php');
// Подключает и инициализирует все файлы шаблонов одиночки
require_once('application/singleton_video.php');





	/////////////////////////////////////////////////////////////////////////////
	
		// Отображает видеоряд
		
	/////////////////////////////////////////////////////////////////////////////
final class ContentVideo
{
	
	
	// Извлекает из БД данные о видео для дальнейшего вывода его на страницу
	public function OutputVideo($numid)
	{
		$sql = MysqlEx::_Instance();
		//$out = array(); // Контент выходных данных

		if (isset($numid) && intval($numid))
		{
		
		$query = "SELECT
		 `".ConfigMySQL::db_prefix."video`.`video_id` AS video_id , 
		 `".ConfigMySQL::db_prefix."video`.`video_name` AS video_name ,
		 `".ConfigMySQL::db_prefix."video`.`videosubdir_id` AS videosubdir_id ,
		 `".ConfigMySQL::db_prefix."video`.`video_link` AS video_link, 
		 `".ConfigMySQL::db_prefix."video`.`video_linkimg` AS video_linkimg , 
		 `".ConfigMySQL::db_prefix."video`.`video_linkimg_scr` AS video_linkimg_scr , 
		 `".ConfigMySQL::db_prefix."video`.`video_kategory` AS video_kategory , 
		 `".ConfigMySQL::db_prefix."video`.`kategory_id` AS kategory_id , 
		 `".ConfigMySQL::db_prefix."video`.`video_posts` AS video_posts , 
		 `".ConfigMySQL::db_prefix."video`.`video_like` AS video_like , 
		 `".ConfigMySQL::db_prefix."video`.`video_view` AS video_view ,
		 `".ConfigMySQL::db_prefix."video`.`video_download` AS video_download ,
		 `".ConfigMySQL::db_prefix."video`.`video_dobavlen` AS video_dobavlen , 
		 `".ConfigMySQL::db_prefix."video`.`video_show` AS video_show ,
		 `".ConfigMySQL::db_prefix."video`.`video_type` AS video_type ,
		 
		 `".ConfigMySQL::db_prefix."videodisck`.`videodisck_name` AS videodisck_name ,
		 `".ConfigMySQL::db_prefix."videodir`.`videodir_dir` AS videodir_dir ,
		 `".ConfigMySQL::db_prefix."videosubdir`.`videosubdir_dir` AS videosubdir_dir
	FROM 
		`".ConfigMySQL::db_prefix."video` 
	LEFT JOIN `".ConfigMySQL::db_prefix."videodisck`  USING( `videodisck_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videodir`  USING( `videodir_id` )
	LEFT JOIN `".ConfigMySQL::db_prefix."videosubdir`  USING( `videosubdir_id` )
	
	WHERE 
		 `video_show` = 'Y'
		 AND
		 `video_id`= ".$numid."
	LIMIT 1;";
	
			$sql->sql_query($query);
			//while($massiv = $sql->sql_fetch_array())
			if($massiv = $sql->sql_fetch_array())
			{
				//array_push($out, $massiv);
				
				// Преобразуем ссылку на видео на локальный сервер
				//$massiv['video_link'] = 'https://'.$_SERVER['HTTP_HOST'].'/video/'.
				$massiv['video_link'] = 'https://hardprivate.com/video/'.
					$massiv['videodisck_name'] . '/' . $massiv['video_type'] . '/' .
					$massiv['videodir_dir'] . '/';
				$massiv['video_link_abs'] = '/home/gt/html/ha0/public_html/video/'.
					$massiv['videodisck_name'] . '/' . $massiv['video_type'] . '/' .
					$massiv['videodir_dir'] . '/';
				if ($massiv['videosubdir_id'] > 0)
				{
					$massiv['video_link'] .= $sql->myUrlEncode($massiv['videosubdir_dir']) . '/';
					$massiv['video_link_abs'] .= $massiv['videosubdir_dir'] . '/';
				}
				$massiv['video_link'] .= $massiv['video_name'].'.mp4';
				$massiv['video_link_abs'] .= $massiv['video_name'].'.mp4';
				//var_dump($massiv['video_link_abs']);
				return $massiv;
			}
			else return false;
		
		//return $out;
		}
		else return false;
	}
}	
	/////////////////////////////////////////////////////////////////////////////////////






/*////////////////////////////////////////////////////////////////

	Основной функционал загрузки файла


////////////////////////////////////////////////////////////////*/

require_once('../../../vendor/autoload.php');

$cv = new ContentVideo();


if(!(isset($_GET['v']) && intval($_GET['v']) ) ) exit(0);
if(!$massivSQL = $cv->OutputVideo($_GET['v'])) exit(0);

// $massivSQL; 


header('Content-type: video/mp4');
//header('Transfer-Encoding: chunked');
//header('Content-disposition: inline');
header("Content-Transfer-Encoding: binary");
header('Content-Disposition: attachment; filename="'.$massivSQL['video_id'].'.mp4"');
header("Content-Length: ".filesize($massivSQL['video_link_abs']));

$handle = @fopen($massivSQL['video_link_abs'], "rb") or die("Couldn't get handle");
if ($handle) {
    while (!feof($handle)) {
        echo fread($handle, 32*1024);
        // Process buffer here..
    }
    @fclose($handle);
}
exit(0);



//echo $massivSQL['video_link_abs'];

/////////////////////////////////////////////////////////////////////

/*
use React\EventLoop\Factory;
use React\Filesystem\Filesystem;
use React\Stream\ReadableResourceStream;

$loop = Factory::create();

$filePath = $massivSQL['video_link_abs'];

$file = new ReadableResourceStream(fopen($filePath, 'rb'), $loop);
$fs = Filesystem::create($loop);

//When using the ReadableResourceStream, the text is printed and the script terminates immediately:

/*
$file->on('data', function ($contents) {
    echo $contents;
});
*/
/*
$fs->getContents($filePath)
    ->then(function ($contents) {
        echo $contents;
    });

$loop->run();
*/

////////////////////////////////////////////////////////////////////
/*

$loop = \React\EventLoop\Factory::create();

$filesystem = \React\Filesystem\Filesystem::create($loop);
echo 'Using ', get_class($filesystem->getAdapter()), PHP_EOL;
$filesystem->file(__FILE__)->getContents()->then(function ($contents) {
    echo $contents, PHP_EOL;
}, function ($e) {
    echo $e->getMessage(), PHP_EOL;
});

$loop->run();
*/

////////////////////////////////////////////////////////////////////

/*
$loop = \React\EventLoop\Factory::create();
$filesystem = \React\Filesystem\Filesystem::create($loop);

$filesystem->file($massivSQL['video_link_abs'])->open('r')->then(function ($stream) use ($node) {
    $buffer = '';
    $deferred = new \React\Promise\Deferred();
    $stream->on('data', function ($data) use (&$buffer) {
        $buffer += $data;
    });
    $stream->on('end', function ($data) use ($stream, $deferred, &$buffer) {
        $stream->close();
        $deferred->resolve(&$buffer);
    });
    return $deferred->promise();
});

// run the application
$loop->run();
*/

////////////////////////////////////////////////////////////////////

// init the event loop
/*$loop = \React\EventLoop\Factory::create();

$filesystem = \React\Filesystem\Filesystem::create($loop);

$server = new \React\Http\Server($loop, function (\Psr\Http\Message\ServerRequestInterface $request) use ($filesystem) 
{
    $file = $filesystem->file($massivSQL['video_link_abs']);

	// file — Читает содержимое файла и помещает его в массив
    return $file->open('r')->then(
        function (\React\Filesystem\Stream\ReadableStream $stream) 
		{
            return new \React\Http\Response(200, ['Content-Type' => 'video/mp4'], $stream);
        }
    );
});


// run the application
$loop->run();
*/





///////////////////////////////////////////////////////////////////

// статистика для файла: зависла

/*

$loop = React\EventLoop\Factory::create();

$filesystem = React\Filesystem\Filesystem::create($loop);
echo 'Using ', get_class($filesystem->getAdapter()), PHP_EOL;
$filesystem->file($massivSQL['video_link_abs'])->stat()->then(function ($data) {
    foreach ($data as $key => $value) {
        echo $key, ': ', var_export($value, true), PHP_EOL;
    }
}, function ($e) {
    echo $e->getMessage(), PHP_EOL;
});

$loop->run();


*/


//////////////////////////////////////////////////////////////////

/* //загрузка файла целиком, 15 секунд - превышение запроса для большого файла

$loop = React\EventLoop\Factory::create();

$filesystem = React\Filesystem\Filesystem::create($loop);
echo 'Using ', get_class($filesystem->getAdapter()), PHP_EOL;
$filesystem->file($massivSQL['video_link_abs'])->getContents()->then(function ($contents) {
    echo $contents, PHP_EOL;
}, function ($e) {
    echo $e->getMessage(), PHP_EOL;
});

$loop->run();

*/

///////////////////////////////////////////////////////////////////

/*


$loop = React\EventLoop\Factory::create();
$client = new React\HttpClient\Client($loop);
$filesystem = React\Filesystem\Filesystem::create($loop);



$file = React\Promise\Stream\unwrapWritable($filesystem->file($massivSQL['video_name'].'.mp4')->open('cw'));
$request = $client->request('GET', $massivSQL['video_link']);

$request->on('response', function (React\HttpClient\Response $response) use ($file) {
    $size = $response->getHeaders()['Content-Length'];
    $currentSize = 0;

    $progress = new React\Stream\ThroughStream();
    $progress->on('data', function($data) use ($size, &$currentSize){
        $currentSize += strlen($data);
        echo "Downloading: ", number_format($currentSize / $size * 100), "%\n";
    });

    $response->pipe($progress)->pipe($file);
});

$request->end();
$loop->run();

*/

?>