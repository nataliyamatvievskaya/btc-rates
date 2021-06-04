<?php


namespace App\ControllerV1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

class Base
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

       $this->__check($request);
       $class = 'App\\Actions\\' . ucfirst($request->getQueryParams()['method']);
       return (new $class)->run($request, $response, $args);

    }

    /**
     * Request checker
     * @param $request
     * @throws HttpBadRequestException
     * @throws HttpForbiddenException
     */
    private function __check($request) {

        $header =  $request->getHeader('Authorization');

        if (empty($header) || substr($header[0], 0, 7) !== 'Bearer ') {
           throw new HttpBadRequestException($request, 'No auth token');
        }

        $token =  trim(substr($header[0], 7));

        if ($token  != APP_ACCESS_TOKEN) {
            throw new HttpForbiddenException($request, 'Invalid token');
        }
        $method = $request->getQueryParams()['method'] ?? null;
        if(!$method || !class_exists('App\\Actions\\'. ucfirst($method))) {
            throw new HttpBadRequestException($request, 'Invalid method');
        }
    }
}