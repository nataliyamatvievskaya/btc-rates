<?php


namespace App\Rates\Enum;

/**
 * From API available currencies
 * Class Currency
 * @package App\Rates\Enum
 */
class Currency
{
	const USD = 'USD';
	const AUD = 'AUD';
	const BRL = 'BRL';
	const CAD = 'CAD';
	const CHF = 'CHF';
	const CLP = 'CLP';
	const CNY = 'CNY';
	const DKK = 'DKK';
	const EUR = 'EUR';
	const GBP = 'GBP';
	const HKD = 'HKD';
	const INR = 'INR';
	const ISK = 'ISK';
	const KRW = 'KRW';
	const NZD = 'NZD';
	const PLN = 'PLN';
	const RUB = 'RUB';
	const SEK = 'SEK';
	const SGD = 'SGD';
	const THB = 'THB';
	const TRI = 'TRY';
	const TWD = 'TWD';

	static public function getConstants() {
		$oClass = new \ReflectionClass(__CLASS__);
		return $oClass->getConstants();
	}


}