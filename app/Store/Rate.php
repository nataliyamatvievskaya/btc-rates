<?php


namespace App\Store;


use App\Cache\RedisCache;
use App\Rates\Enum\Currency;
use App\Rates\Ticker;

/**
 * Store
 * Class Rate
 * @package App\Store
 */
class Rate
{
	public function __construct() {
		RedisCache::init();
	}

	/**
	 * Get Rates from external api
	 * @return mixed
	 */
	private function __getFromExrernal(): array{

		$rates = json_decode((new Ticker())->getAll(), true);

		if (APP_CACHE_ON) {
			foreach ($rates  as $currency=>$rate) {
				RedisCache::set($currency, json_encode($rates[$currency]));

			}
		}
		return $rates;
	}

	/**
	 * Get rate from store
	 * @param $currency
	 * @return bool|mixed
	 */
	private function __getFromCache(string $currency) {
		if (!APP_CACHE_ON) {
			return false;
		}
		return json_decode(RedisCache::get($currency), true);
	}

	/**
	 * Get All available Rates
	 * @return array|mixed
	 */
	public function getAll(): array{

		$rates = [];
		foreach (Currency::getConstants() as $currency) {
			$rates[$currency] =  $this->__getFromCache($currency);
			if (!$rates[$currency]) {
				return $this->__getFromExrernal();
			}
		}
		return $rates;
	}

	/**
	 * Get rate by currency
	 * @param string $currency
	 * @return bool|mixed
	 */
	public function getRate(string $currency): array {

		$rate = $this->__getFromCache($currency);

		if (!$rate) {
			return $this->__getFromExrernal()[$currency];
		}

		return $rate;
	}

}