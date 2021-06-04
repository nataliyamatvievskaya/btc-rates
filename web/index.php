<?php
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Slim\ResponseEmitter;
use \Slim\Factory\AppFactory;
use \Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use \Slim\Psr7\Response;
use \App\ControllerV1\Base;



require_once '../vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once '../app/Config' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = AppFactory::create();

$app->any('/api/v1', new Base());

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
    $response->getBody()->write("Checking health: it works");
    return $response;
});

try {
    $app->run();
} catch (HttpBadRequestException | HttpForbiddenException $e) {
    $Response = (new Response($e->getCode()));
    $Response->getBody()->write(json_encode([
    	'status' => 'error',
		'code' => $e->getCode(),
		'message' => $e->getMessage()
	]));
	(new ResponseEmitter())->emit($Response);
} catch (Exception $e) {
	$Response = (new Response(500));
	$Response->getBody()->write(json_encode([
		'status' => 'error',
		'code' => $e->getCode(),
		'message' => $e->getMessage()
	]));
	(new ResponseEmitter())->emit($Response);
}

