<?php
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Slim\ResponseEmitter;
use \Slim\Factory\AppFactory;

require_once '../vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once '../app/Config' . DIRECTORY_SEPARATOR . 'bootstrap.php';

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$app = AppFactory::create();

$app->group('/v1', function($group){
    $group->get('/rates[/]', new \App\Controller\Rates());
	$group->get('/convert[/]', new \App\Controller\Convert());
})->add(\App\Permissions::class);

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
    $response->getBody()->write("Checking health: it works");
    return $response;
});
try {
    $app->run();
} catch (\Slim\Exception\HttpBadRequestException | \Slim\Exception\HttpForbiddenException $e) {
    $Response = (new \Slim\Psr7\Response($e->getCode()));
    $Response->getBody()->write(json_encode([
    	'status' => 'error',
		'code' => $e->getCode(),
		'message' => $e->getMessage()
	]));
	(new ResponseEmitter())->emit($Response);
} catch (Exception $e) {
	$Response = (new \Slim\Psr7\Response(500));
	$Response->getBody()->write(json_encode([
		'status' => 'error',
		'code' => $e->getCode(),
		'message' => $e->getMessage()
	]));
	(new ResponseEmitter())->emit($Response);
}

