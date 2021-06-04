<?php


namespace App\Actions;


use App\Comission;
use App\Rates\Enum\Currency;
use App\Rates\Enum\Fields;
use App\Store\Rate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

/**
 * Getting rates operation
 * Class Rates
 * @package App\Controller
 */
class Rates implements IActions {

    /**
     * @param ServerRequestInterface $request
     * @return array|mixed
     * @throws HttpBadRequestException
     */
    public function check(ServerRequestInterface $request) {

        if ($request->getMethod() != 'GET') {
            throw new HttpBadRequestException($request, 'Request method must be GET');
        }

        $currency = $request->getQueryParams()['currency'] ?? false;

        if ($currency && !in_array($currency, Currency::getConstants())) {
            throw new HttpBadRequestException($request, 'Invalid  param: currency');
        }
        return compact('currency');
    }

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return ResponseInterface
	 */
    public function run(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $params = $this->check($request);

        /** @var Rate $Rate */
    	$Rate = new Rate();
        $result = [];
        if (!$params['currency']) {
            $rates = $Rate->getAll();
            foreach ($rates as $currency=>$rate) {
                $result[$currency] = Comission::add($rate[Fields::BUY]);
            }
            asort($result);
        } else {
            $rate = $Rate->getRate($params['currency']);
            $result[$params['currency']] = Comission::add($rate[Fields::BUY]);
        }

        $response->getBody()->write(json_encode([
        	'code' => 200,
			'status' => 'success',
			'data' => $result
		]));

        return $response->withHeader('Content-Type', 'application/json');
    }


}