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
 * Class Convert
 * Convert rates operation
 * @package App\Controller
 */
class Convert implements IActions
{
    /**
     * @param ServerRequestInterface $request
     * @return array|mixed
     * @throws HttpBadRequestException
     */
    public function check(ServerRequestInterface $request) {

        if ($request->getMethod() != 'POST') {
            throw new HttpBadRequestException($request, 'Request method must be POST');
        }
        $params = $request->getParsedBody();

        $currencyFrom = $params['currency_from'] ?? false;
        $currencyTo= $params['currency_to'] ?? false;
        $value = $params['value'] ?? false;

        if (!$currencyFrom || (!in_array($currencyFrom, Currency::getConstants()) && $currencyFrom != APP_CONVERT_CURRENCY)) {
            throw new HttpBadRequestException($request, 'Invalid  param: currency_from');
        }

        if (!$currencyTo || (!in_array($currencyTo, Currency::getConstants()) && $currencyTo != APP_CONVERT_CURRENCY)) {
            throw new HttpBadRequestException($request, 'Invalid  param: currency_to');
        }

        if (
            $currencyFrom != APP_CONVERT_CURRENCY  && $currencyTo != APP_CONVERT_CURRENCY ||
            $currencyFrom == $currencyTo
        ) {
            throw new HttpBadRequestException($request, sprintf('Service can`t convert from %s to %s', $currencyFrom, $currencyTo));
        }

        if (!$value || $value < 0) {
            throw new HttpBadRequestException($request, 'Invalid  param: value');
        }

        if ($currencyFrom != APP_CONVERT_CURRENCY && $value < APP_MIN_VALUE_FOR_CONVERT) {
            throw new HttpBadRequestException($request, 'Invalid  param: min value = ' . APP_MIN_VALUE_FOR_CONVERT);
        }
        return compact('currencyFrom', 'currencyTo', 'value');
    }
	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return ResponseInterface
	 * @throws HttpBadRequestException
	 */
	public function run(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $params = $this->check($request);

		$Rate = new Rate();
        $value = $params['value'];
		if ($params['currencyFrom'] == APP_CONVERT_CURRENCY) {
			$rate =  $Rate->getRate($params['currencyTo']);
			$rateForConvert = Comission::add($rate[Fields::BUY]);
            $converted = number_format($params['value']  * $rateForConvert, 2, '.', '');
		} else {
            $rate =  $Rate->getRate($params['currencyFrom']);
			$rateForConvert = Comission::add($rate[Fields::SELL]);
            $converted =  number_format($value / $rateForConvert, 10, '.', '');
		}

		$response->getBody()->write(json_encode([
			'code' => 200,
			'status' => 'success',
			'data' => [
				'currency_from' => $params['currencyFrom'],
				'currency_to' => $params['currencyTo'],
				'value' => $value,
				'converted_value' => $converted,
				'rate' => $rateForConvert
			]
		]));

		return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

	}


}