<?php


namespace App\Service;


use App\Cache\Comission;
use App\Cache\RedisCache;
use App\Rates\Enum\Currency;
use App\Rates\Enum\Fields;
use App\Rates\Ticker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Rates
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args) {

        $rates =$this->__getAll();

		$currency = $request->getQueryParams()['currency'] ?? false;


        Comission::add($rates);

        $result = [];
        if (!$currency) {
			foreach ($rates as $currency=>$rate) {
				$result[$currency] = $rate[Fields::BUY];
			}
		} else {
        	$result[$currency] = $rates[$currency][Fields::BUY];
		}

        $response->getBody()->write(json_encode([
        	'code' => 200,
			'status' => 'success',
			'data' => json_encode($result)
		]));
        return $response->withHeader('Content-Type', 'application/json');



    }

    private function __getFromServer() {

    	$rates = json_decode((new Ticker())->getAll(), true);

    	foreach ($rates  as $currency=>$rate) {
			RedisCache::set('rates', json_encode($rates));
		}

    	return $rates;
	}

    private function __getAll(){

    	RedisCache::init();
    	$rates = [];
		foreach (Currency::getConstants() as $currency) {
			$rates[$currency] =  json_decode(RedisCache::get($currency), true);
			if (!$rates[$currency]) {
				return $this->__getFromServer();
			}
		}
	}

}