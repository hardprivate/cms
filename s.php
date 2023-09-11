<?php

require_once( 'vendor/autoload.php' );
require_once('v.php');




//if(!(isset($_GET['v']) && intval($_GET['v']) ) ) exit(0);
//if(!$massivSQL = $cv->OutputVideo($_GET['v'])) exit(0);
//$cv->OutputVideo($queryParams['foo']);

$loop = React\EventLoop\Factory::create();

$server = new React\Http\Server($loop, function (Psr\Http\Message\ServerRequestInterface $request) use ($loop) {

	$cv = new ContentVideo();
	$queryParams = $request->getQueryParams();
	$massivSQL = $cv->OutputVideo(intval($queryParams['v']));

	$video = new React\Stream\ReadableResourceStream( 
       fopen($massivSQL['video_link_abs'], 'rb'), $loop 
     ); 

    return new React\Http\Message\Response(
        200,
        array(
            //'Content-Type' => 'application/octet-data',
            'Content-Type' =>'video/mp4' ,
			//'Content-Length' => $stream->getSize() ,
			'Content-Transfer-Encoding' => 'binary' 
        ),
        $video
		//$massivSQL['video_link_abs']
    );
});

$socket = new React\Socket\Server('0.0.0.0:1935', $loop
//$socket = new React\Socket\Server('0.0.0.0:1935', $loop
	//, array(   'tls' => array( 'local_cert' => 'localhost.pem' ))
	);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;

$loop->run();