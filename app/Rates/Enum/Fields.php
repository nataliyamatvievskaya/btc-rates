<?php


namespace App\Rates\Enum;

/**
 * From API Fields
 * Class Fields
 * @package App\Rates\Enum
 */
class Fields
{

	const M15 = '15m';
	const LAST = 'last';
	const SELL = 'sell';
	const BUY = 'buy';
	const SYMBOL = 'symbol';

	static public $forComissionAdd = [
		self::M15,
		self::BUY,
		self::SELL,
		self::LAST
	];


}