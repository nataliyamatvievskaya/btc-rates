<?php
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Slim\ResponseEmitter;
use \Slim\Factory\AppFactory;

require_once '../vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
///require_once '../app' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = AppFactory::create();

$app->get('/rates[/]', new \App\Service\Rates());

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
    $response->getBody()->write("Checking health: it works");
    return $response;
});
try {
    $app->run();
} catch (\Exception $e) {
    var_dump($e);die;
    $errResponse = (new \Slim\Psr7\Response($e->getCode()));
    $errResponse->getBody()->write(json_encode(['err' => $e->getMessage()]));
    $responseEmitter = new ResponseEmitter();
    $responseEmitter->emit($errResponse);
}
