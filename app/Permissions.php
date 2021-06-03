<?php


namespace App;


use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;

class Permissions
{
    private function __checkBearer(\Slim\Psr7\Request $request) {

        $header =  $request->getHeader('Authorization');

        if (empty($header)) {
            return false;
        }

        if (substr($header, 0, 7) !== 'Bearer ') {
            return false;
        }
        $token =  trim(substr($header, 7));

        if ($token  == APP_ACCESS_TOKEN) {
            return $token;
        }
        return false;
    }


    public function __invoke(\Slim\Psr7\Request $request, RequestHandlerInterface $handler) {

        if(!$this->__checkBearer($request)) {
            throw new \Slim\Exception\HttpForbiddenException($request);
        }

    }
}