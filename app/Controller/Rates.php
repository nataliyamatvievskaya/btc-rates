<?php


namespace App\Controller;


use App\Comission;
use App\Rates\Enum\Fields;
use App\Store\Rate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Getting rates operation
 * Class Rates
 * @package App\Controller
 */
class Rates {

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return ResponseInterface
	 */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

    	$rates = (new Rate())->getAll();

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


}