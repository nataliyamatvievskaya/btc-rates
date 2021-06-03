<?php


namespace App\Rates;


/**
 * Get tickers from API
 * https://blockchain.info/ru/ticker
 * Class Ticker
 * @package App\Rates
 */
class Ticker extends Base
{

    protected $_method = 'ticker';

	/**
	 * @return string
	 */
    public function getAll() {
        return $this->get();
    }

}