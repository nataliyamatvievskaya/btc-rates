<?php


namespace App\Service;


use App\Cache\Comission;
use App\Cache\RedisCache;
use App\Rates\Ticker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Rates
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args) {

        RedisCache::init();
        $rates = RedisCache::get('rates');

        if (!$rates || empty($rates)) {
            $rates = (new Ticker())->getAll();
            RedisCache::set('rates', $rates);
        }
        $rates = json_decode($rates, true);

        Comission::add($rates);






                $response->getBody()->write(json_encode($rates));
                return $response
                    ->withHeader('Content-Type', 'application/json');



    }

}