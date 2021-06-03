<?php


namespace App\Controller;


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
class Convert
{
	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return ResponseInterface
	 * @throws HttpBadRequestException
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

		$currencyFrom = $request->getQueryParams()['currency_from'] ?? false;
		$currencyTo= $request->getQueryParams()['currency_to'] ?? false;
		$value = $request->getQueryParams()['value'] ?? false;

		if (!$currencyFrom || (!in_array($currencyFrom, Currency::getConstants()) && $currencyFrom != APP_CONVERT_CURRENCY)) {
			throw new HttpBadRequestException($request, 'Invalid  param: currency_from');
		}

		if (!$currencyTo || (!in_array($currencyTo, Currency::getConstants()) && $currencyTo != APP_CONVERT_CURRENCY)) {
			throw new HttpBadRequestException($request, 'Invalid  param: currency_to');
		}

		if ($currencyFrom != APP_CONVERT_CURRENCY  && $currencyTo != APP_CONVERT_CURRENCY) {
			throw new HttpBadRequestException($request, 'Service can convert from BTC or to BTC');
		}

		if ($currencyFrom == $currencyTo) {
			throw new HttpBadRequestException($request, 'Service can`t convert from BTC to BTC');
		}

		if (!$value || $value < 0) {
			throw new HttpBadRequestException($request, 'Invalid  param: value');
		}

		$Rate = new Rate();

		if ($currencyFrom == APP_CONVERT_CURRENCY) {
			$rate = [
				$currencyTo => $Rate->getRate($currencyTo)
			];
			Comission::add($rate);
			$rateForConvert = $rate[$currencyTo][Fields::BUY];
		} else {
			$rate = [
				$currencyFrom => $Rate->getRate($currencyFrom)
			];
			Comission::add($rate);
			$rateForConvert = $rate[$currencyFrom][Fields::SELL];
		}

		$response->getBody()->write(json_encode([
			'code' => 200,
			'status' => 'success',
			'data' => json_encode([
				'currency_from' => $currencyFrom,
				'currency_to' => $currencyTo,
				'value' => $value,
				'converted_value' => $value * $rateForConvert,
				'rate' => $rateForConvert
			])
		]));

		return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

	}


}