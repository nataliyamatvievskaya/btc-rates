<?php


namespace App\Actions;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface IActions {

    /**
     * find and check request params
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function check(ServerRequestInterface $request);

    /**
     * Action run
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
}