<?php


namespace App\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Base
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args) {

    }

}