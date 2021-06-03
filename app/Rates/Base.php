<?php


namespace App\Rates;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Base  class for working with API
 * https://www.blockchain.com/api/exchange_rates_api
 * Class Base
 * @package App\Rates
 */
abstract class Base
{
    protected $_url = 'https://blockchain.info/';

    public function __construct() {
        $this->client = new Client(['base_uri' => $this->_url]);
    }

    protected function _formatUrl($data = []) {

        return
            $this->_url . $this->_method .'?' . http_build_query(array_merge(
                $data
            ));
    }

	/**
	 * base request
	 * @param Request $request
	 * @return string
	 */
    protected function _request(Request $request){

        try {
            /** @var Response $response */
            $response = $this->client->send($request);
            return $response->getBody(true)->getContents();

        } catch (\Throwable $e) {
			throw $e;
        }
    }

	/**
	 * GET request
	 * @param array $data
	 * @return string
	 */
    public function get($data = []) {

        $request = new Request(
            'GET',
            $this->_formatUrl($data)
        );

        return $this->_request($request);
    }

}