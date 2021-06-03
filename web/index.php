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
    $group->get('/rates/', new \App\Controller\Rates());
})->add(\App\Permissions::class)->add(\Slim\Middleware\ErrorMiddleware::class);

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
    $response->getBody()->write("Checking health: it works");
    return $response;
});
try {
    $app->run();
} catch (\Slim\Exception\HttpForbiddenException $e) {
    // $errResponse = (new \Slim\Psr7\Response($e->getCode()));
    // $errResponse->getBody()->write(json_encode(['err' => $e->getMessage()]));
    // $responseEmitter = new ResponseEmitter();
    //  $responseEmitter->emit($errResponse);
}

